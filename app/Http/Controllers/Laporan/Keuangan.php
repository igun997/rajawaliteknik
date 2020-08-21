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
        $status = [];
        $status[] = [
            "name"=>"Semua",
            "value"=>-1,
        ];
        $status[] = [
            "name"=>StatusTransaction::lang(StatusTransaction::CONFIRMED),
            "value"=>StatusTransaction::CONFIRMED,
        ];
        $status[] = [
            "name"=>StatusTransaction::lang(StatusTransaction::REJECTED),
            "value"=>StatusTransaction::REJECTED,
        ];
        $status[] = [
            "name"=>StatusTransaction::lang(StatusTransaction::WAITING_CONFIRMATION),
            "value"=>StatusTransaction::WAITING_CONFIRMATION,
        ];

        $type = [];
        $type[] = [
            "name"=>"Semua",
            "value"=>-1,
        ];
        $type[] = [
            "name"=>TypeTransaction::lang(TypeTransaction::IN),
            "value"=>TypeTransaction::IN,
        ];
        $type[] = [
            "name"=>TypeTransaction::lang(TypeTransaction::OUT),
            "value"=>TypeTransaction::OUT,
        ];

        $ref_type = [];
        $ref_type[] = [
            "name"=>"Semua",
            "value"=>-1,
        ];
        $ref_type[] = [
            "name"=>RefType::lang(RefType::CASHBON),
            "value"=>RefType::CASHBON,
        ];
        $ref_type[] = [
            "name"=>RefType::lang(RefType::ORDER),
            "value"=>RefType::ORDER,
        ];
        $ref_type[] = [
            "name"=>RefType::lang(RefType::PURCHASE),
            "value"=>RefType::PURCHASE,
        ];






        return view("laporan.keuangan",[
            "title"=>"Laporan Keuangan",
            "status"=>$status,
            "type"=>$type,
            "ref"=>$ref_type,
        ]);
    }
    public function generate_pdf(Request $req)
    {
        $req->validate([
            "from"=>"required",
            "to"=>"required",
            "status"=>"required",
            "type"=>"required",
            "ref"=>"required",
        ]);


        $title = 'Laporan Data Keuangan'; // Report title

        $meta = [ // For displaying filters description on header
            'Periode ' => date("d/m/Y",strtotime($req->from)) . ' - ' . date("d/m/Y",strtotime($req->to))
        ];

        $queryBuilder = Transaction::whereBetween('created_at', [$req->from, $req->to]);

        if ($req->status != -1){
            $queryBuilder->where(["status"=>$req->status]);
        }
        if ($req->type != -1){
            $queryBuilder->where(["type"=>$req->type]);
        }
        if ($req->ref != -1){
            $queryBuilder->where(["ref_type"=>$req->ref]);
        }
        $queryBuilder->orderBy("created_at","DESC");

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
            "Keterangan"=>"descriptions",
            "Status"=>function($res){
                return StatusTransaction::lang($res->status);
            },
            'Dibuat'=>'created_at',
        ];

        return PdfReport::of($title, $meta, $queryBuilder, $columns)->showTotal(["Total"=>"point"])->setOrientation('landscape')->download("keuangan_".time());


    }
    public function generate_excel(Request $req)
    {
        $req->validate([
            "from"=>"required",
            "to"=>"required",
            "status"=>"required",
            "type"=>"required",
            "ref"=>"required",
        ]);


        $title = 'Laporan Data Keuangan'; // Report title

        $meta = [ // For displaying filters description on header
            'Periode ' => date("d/m/Y",strtotime($req->from)) . ' - ' . date("d/m/Y",strtotime($req->to))
        ];

        $queryBuilder = Transaction::whereBetween('created_at', [$req->from, $req->to]);

        if ($req->status != -1){
            $queryBuilder->where(["status"=>$req->status]);
        }
        if ($req->type != -1){
            $queryBuilder->where(["type"=>$req->type]);
        }
        if ($req->ref != -1){
            $queryBuilder->where(["ref_type"=>$req->ref]);
        }
        $queryBuilder->orderBy("created_at","DESC");

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

        return ExcelReport::of($title, $meta, $queryBuilder, $columns)->showTotal(["Total"=>"point"])->setOrientation('landscape')->download("penjualan_".time());


    }
}
