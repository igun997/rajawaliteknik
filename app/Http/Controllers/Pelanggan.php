<?php

namespace App\Http\Controllers;

use App\Casts\StatusCustomer;
use App\ClassesRule\CRUDInterface;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;

class Pelanggan  extends Controller implements CRUDInterface
{

    public function index()
    {
        $data = Customer::all();
        foreach ($data as $index => &$datum) {
            $datum->status_lang = StatusCustomer::lang($datum->status);
        }
        return view("pelanggan.home",[
            "title"=>"Data Pelanggan",
            "data"=>$data
        ]);
    }

    public function add()
    {
        return view("pelanggan.form",[
            "title"=>"Tambah Pelanggan ",
            "data"=>[],
            "disabled"=>[],
            "route"=>route("pelanggan.add.process")
        ]);
    }

    public function update($id)
    {
        $find = Customer::where(["id"=>$id]);
        if ($find->count() === 0){
            return back()->withErrors(["msg"=>"Pelanggan Tidak Ditemukan"]);
        }

        return view("pelanggan.form",[
            "title"=>"Update Data Pelanggan ",
            "data"=>$find->first(),
            "disabled"=>[],
            "route"=>route("pelanggan.update.process",$id)
        ]);
    }

    public function update_process(Request $req, $id)
    {
        $req->validate([
            "name"=>"required",
            "percentage_discount"=>"numeric",
            "address"=>"required",
        ]);
        $data = $req->all();
        $data["has_discount"] = ($data["percentage_discount"])?1:0;
        $data["status"] = StatusCustomer::ACTIVE;
        unset($data["_token"]);

        $make = Customer::where(["id"=>$id])->update($data);
        if ($make){
            return  back()->with([
                "msg"=>"Update Pelanggan Berhasil",
                "url"=>route("pelanggan.list")
            ]);
        }else{
            return back()->withErrors(["msg"=>"Update Pelanggan Gagal"]);
        }
    }

    public function add_process(Request $req)
    {
        $req->validate([
            "name"=>"required",
            "address"=>"required",
        ]);
        $data = $req->all();
        $data["has_discount"] = 0;
        $data["status"] = StatusCustomer::ACTIVE;

        $make = Customer::create($data);
        if ($make){
            return  back()->with([
                "msg"=>"Pelanggan Berhasil Di Tambahkan",
                "url"=>route("pelanggan.list")
            ]);
        }else{
            return back()->withErrors(["msg"=>"Pembuatan Pelanggan Gagal"]);
        }
    }
}
