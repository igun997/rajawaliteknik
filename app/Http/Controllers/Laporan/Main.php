<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Main extends Controller
{
    public function index()
    {
        $istLaporan = [];
        $istLaporan[0] = [
            [
                "name"=>"Keuangan",
                "route"=>route("laporan.keuangan.home")
            ],[
                "name"=>"Pelanggan",
                "route"=>route("laporan.pelanggan.home")
            ],[
                "name"=>"Penjualan",
                "route"=>route("laporan.penjualan.home")
            ]
        ];
        $istLaporan[1] = [
            [
                "name"=>"Pelanggan",
                "route"=>route("laporan.pelanggan.home")
            ],[
                "name"=>"Penjualan",
                "route"=>route("laporan.penjualan.home")
            ]
        ];
        $istLaporan[2] = [
            [
                "name"=>"Keuangan",
                "route"=>route("laporan.keuangan.home")
            ]
        ];
        return view("laporan.main",[
            "title"=>"Laporan",
            "data"=>$istLaporan
        ]);
    }
}
