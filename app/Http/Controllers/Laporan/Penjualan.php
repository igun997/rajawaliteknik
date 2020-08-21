<?php

namespace App\Http\Controllers\Laporan;

use App\Casts\StatusOrder;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use PdfReport;
use ExcelReport;
class Penjualan extends Controller
{
    public function index()
    {
        return view("laporan.penjualan",[
            "title"=>"Laporan Penjualan"
        ]);
    }
    public function generate_pdf(Request $req)
    {
        $req->validate([
            "from"=>"required",
            "to"=>"required"
        ]);


        $title = 'Laporan Data Penjualan'; // Report title

        $meta = [ // For displaying filters description on header
            'Periode ' => date("d/m/Y",strtotime($req->from)) . ' - ' . date("d/m/Y",strtotime($req->to))
        ];

        $queryBuilder = Order::whereBetween('created_at', [$req->from, $req->to])
            ->orderBy("created_at","DESC");

        $columns = [ // Set Column to be displayed
            'Nota Faktur' => 'invoice_number',
            'Nama Pelanggan' => function($res){
               return $res->customer->name;
            },
            "Total"=>function($res){
                return number_format($res->total);
            },
            "Staff"=>function($res){
                return  $res->user->name;
            },
            "Diskon"=>function($res){
                return number_format($res->discount);
            },
            "Ket"=>"additional_info",
            'Status' => function($result) {
                return StatusOrder::lang($result->status);
            },
            'Dibuat'=>'created_at',
        ];

        return PdfReport::of($title, $meta, $queryBuilder, $columns)->setOrientation('landscape')->download("penjualan_".time());


    }
    public function generate_excel(Request $req)
    {
        $req->validate([
            "from"=>"required",
            "to"=>"required"
        ]);


        $title = 'Laporan Data Penjualan'; // Report title

        $meta = [ // For displaying filters description on header
            'Periode ' => date("d/m/Y",strtotime($req->from)) . ' - ' . date("d/m/Y",strtotime($req->to))
        ];

        $queryBuilder = Order::whereBetween('created_at', [$req->from, $req->to])
            ->orderBy("created_at","DESC");

        $columns = [ // Set Column to be displayed
            'Nota Faktur' => 'invoice_number',
            'Nama Pelanggan' => function($res){
                return $res->customer->name;
            },
            "Total"=>function($res){
                return $res->total;
            },
            "Staff"=>function($res){
                return  $res->user->name;
            },
            "Diskon"=>function($res){
                return $res->discount;
            },
            "Ket"=>"additional_info",
            'Status' => function($result) {
                return StatusOrder::lang($result->status);
            },
            'Dibuat'=>'created_at',
        ];

        return ExcelReport::of($title, $meta, $queryBuilder, $columns)->setOrientation('landscape')->download("penjualan_".time());


    }
}
