<?php

namespace App\Http\Controllers\Laporan;

use App\Casts\RefType;
use App\Casts\StatusTransaction;
use App\Casts\TypeTransaction;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Purchase;
use App\Models\Transaction;
use Illuminate\Http\Request;

use PdfReport;
use ExcelReport;
class Keuangan extends Controller
{
    public function index()
    {
        return view("laporan.keuangan",[
            "title"=>"Laporan Keuangan",
        ]);
    }
    public function generate_pdf(Request $req)
    {
        $req->validate([
            "from"=>"required",
            "to"=>"required"
        ]);


        $title = 'Laporan Data Keuangan'; // Report title

        $meta = [ // For displaying filters description on header
            'Periode ' => date("d/m/Y",strtotime($req->from)) . ' - ' . date("d/m/Y",strtotime($req->to))
        ];

        $queryBuilder = Transaction::whereBetween('created_at', [$req->from, $req->to])
            ->orderBy("created_at","DESC");

        $columns = [ // Set Column to be displayed
            'Sumber Dana' => function($res){
                return RefType::lang($res->ref_type);
            },
            'Kode Sumber' => function($res){
                if (RefType::ORDER == $res->ref_type || RefType::CASHBON == $res->ref_type){
                    $number = Order::find($res->ref_id)->invoice_number;
                    return $number;

                }elseif (RefType::PURCHASE == $res->ref_type){
                    $number = Purchase::find($res->ref_id)->invoice_number;
                    return $number;
                }else{
                    return null;
                }
            },
            "Total"=>function($res){
                return number_format($res->total);
            },
            "Staff"=>function($res){
                return  $res->user->name;
            },
            "Jenis"=>function($res){
                return TypeTransaction::lang($res->type);
            },
            "Keterangan"=>"descriptions",
            "Status"=>function($res){
                return StatusTransaction::lang($res->status);
            },
            'Dibuat'=>'created_at',
        ];

        return PdfReport::of($title, $meta, $queryBuilder, $columns)->setOrientation('landscape')->download("keuangan_".time());


    }
    public function generate_excel(Request $req)
    {
        $req->validate([
            "from"=>"required",
            "to"=>"required"
        ]);


        $title = 'Laporan Data Keuangan'; // Report title

        $meta = [ // For displaying filters description on header
            'Periode ' => date("d/m/Y",strtotime($req->from)) . ' - ' . date("d/m/Y",strtotime($req->to))
        ];

        $queryBuilder = Transaction::whereBetween('created_at', [$req->from, $req->to])
            ->orderBy("created_at","DESC");

        $columns = [ // Set Column to be displayed
            'Sumber Dana' => function($res){
                return RefType::lang($res->ref_type);
            },
            'Kode Sumber' => function($res){
                if (RefType::ORDER == $res->ref_type || RefType::CASHBON == $res->ref_type){
                    $number = Order::find($res->ref_id)->invoice_number;
                    return $number;

                }elseif (RefType::PURCHASE == $res->ref_type){
                    $number = Purchase::find($res->ref_id)->invoice_number;
                    return $number;
                }else{
                    return null;
                }
            },
            "Total"=>function($res){
                return ($res->total);
            },
            "Staff"=>function($res){
                return  $res->user->name;
            },
            "Jenis"=>function($res){
                return TypeTransaction::lang($res->type);
            },
            "Status"=>function($res){
                return StatusTransaction::lang($res->status);
            },
            'Dibuat'=>'created_at',
        ];

        return ExcelReport::of($title, $meta, $queryBuilder, $columns)->setOrientation('landscape')->download("penjualan_".time());


    }
}
