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
        $status = [];
        $status[] = [
            "name"=>"Semua",
            "value"=>-1,
        ];
        $status[] = [
            "name"=>StatusOrder::lang(StatusOrder::WAITING_PAYMENT),
            "value"=>StatusOrder::WAITING_PAYMENT,
        ];
        $status[] = [
            "name"=>StatusOrder::lang(StatusOrder::PAYMENT_CONFIRMED),
            "value"=>StatusOrder::PAYMENT_CONFIRMED,
        ];
        $status[] = [
            "name"=>StatusOrder::lang(StatusOrder::SHIPPING),
            "value"=>StatusOrder::SHIPPING,
        ];
        $status[] = [
            "name"=>StatusOrder::lang(StatusOrder::SUCCESS),
            "value"=>StatusOrder::SUCCESS,
        ];
        $status[] = [
            "name"=>StatusOrder::lang(StatusOrder::CASHBON),
            "value"=>StatusOrder::CASHBON,
        ];
        $status[] = [
            "name"=>StatusOrder::lang(StatusOrder::CANCELED),
            "value"=>StatusOrder::CANCELED,
        ];

        return view("laporan.penjualan",[
            "title"=>"Laporan Penjualan",
            "data"=>$status
        ]);
    }
    public function generate_pdf(Request $req)
    {
        $req->validate([
            "from"=>"required",
            "to"=>"required",
            "status"=>"required"
        ]);


        $title = 'Laporan Data Penjualan'; // Report title

        $meta = [ // For displaying filters description on header
            'Periode ' => date("d/m/Y",strtotime($req->from)) . ' - ' . date("d/m/Y",strtotime($req->to)),
            "Status Order"=>(($req->status == -1)?"Semua":StatusOrder::lang($req->status))
        ];
        if($req->status == -1){

            $queryBuilder = Order::whereBetween('created_at', [$req->from, $req->to])->orderBy("created_at","DESC");
        }else{

            $queryBuilder = Order::whereBetween('created_at', [$req->from, $req->to])->where(["status"=>$req->status]);
        }

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

        return PdfReport::of($title, $meta, $queryBuilder, $columns)->showTotal(["Total"=>"point","Diskon"=>"point"])->setOrientation('landscape')->download("penjualan_".time());


    }
    public function generate_excel(Request $req)
    {
        $req->validate([
            "from"=>"required",
            "to"=>"required",
            "status"=>"required"
        ]);


        $title = 'Laporan Data Penjualan'; // Report title

        $meta = [ // For displaying filters description on header
            'Periode ' => date("d/m/Y",strtotime($req->from)) . ' - ' . date("d/m/Y",strtotime($req->to)),
            "Status Order"=>(($req->status == -1)?"Semua":StatusOrder::lang($req->status))
        ];
        if($req->status == -1){

            $queryBuilder = Order::whereBetween('created_at', [$req->from, $req->to])->orderBy("created_at","DESC");
        }else{

            $queryBuilder = Order::whereBetween('created_at', [$req->from, $req->to])->where(["status"=>$req->status]);
        }

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

        return ExcelReport::of($title, $meta, $queryBuilder, $columns)->showTotal(["Total"=>"point","Diskon"=>"point"])->setOrientation('landscape')->download("penjualan_".time());


    }
}
