<?php

namespace App\Http\Controllers\Laporan;

use App\Casts\StatusCustomer;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use PdfReport;
use ExcelReport;

class Pelanggan extends Controller
{
    public function index()
    {
        return view("laporan.pelanggan",[
            "title"=>"Laporan Pelanggan"
        ]);
    }
    public function generate_pdf(Request $req)
    {
        $req->validate([
            "from"=>"required",
            "to"=>"required"
        ]);


        $title = 'Laporan Data Pelanggan'; // Report title

        $meta = [ // For displaying filters description on header
            'Terdaftar Pada' => date("d/m/Y",strtotime($req->from)) . ' - ' . date("d/m/Y",strtotime($req->to))
        ];

        $queryBuilder = Customer::whereBetween('created_at', [$req->from, $req->to])
            ->orderBy("created_at","DESC");

        $columns = [ // Set Column to be displayed
            'Nama' => 'name',
            'Alamat' => 'address',
            'Diskon' => function($result){
               return ($result->has_discount)?$result->percentage_discount."%":"Tidak Ada";
             },
             'Status' => function($result) {
                return StatusCustomer::lang($result->status);
             },
             'Terdaftar Pada'=>'created_at',
        ];

        return PdfReport::of($title, $meta, $queryBuilder, $columns)->download("pelanggan_".time());


    }
    public function generate_excel(Request $req)
    {
        $req->validate([
            "from"=>"required",
            "to"=>"required"
        ]);


        $title = 'Laporan Data Pelanggan'; // Report title

        $meta = [ // For displaying filters description on header
            'Terdaftar Pada' => date("d/m/Y",strtotime($req->from)) . ' - ' . date("d/m/Y",strtotime($req->to))
        ];

        $queryBuilder = Customer::whereBetween('created_at', [$req->from, $req->to])
            ->orderBy("created_at","DESC");

        $columns = [ // Set Column to be displayed
            'Nama' => 'name',
            'Alamat' => 'address',
            'Diskon' => function($result){
                return ($result->has_discount)?$result->percentage_discount."%":"Tidak Ada";
            },
            'Status' => function($result) {
                return StatusCustomer::lang($result->status);
            },
            'Terdaftar Pada'=>'created_at',
        ];

        return ExcelReport::of($title, $meta, $queryBuilder, $columns)->download("pelanggan_".time());


    }
}
