<?php

namespace App\Http\Controllers\Produk;

use App\ClassesRule\CRUDInterface;
use App\Http\Controllers\Controller;
use App\Models\ProductSize;
use Illuminate\Http\Request;

class Size extends Controller implements CRUDInterface
{
    //
    public function index()
    {
        $data = ProductSize::orderBy("created_at","desc")->get();
        return view("produk.size.home",[
            "title"=>"Data Ukuran",
            "data"=>$data
        ]);
    }

    public function add()
    {
        return view("produk.size.form",[
            "title"=>"Tambah Ukuran",
            "data"=>[],
            "disabled"=>[],
            "route"=>route("produk.size.add.process")
        ]);
    }

    public function update($id)
    {
       $find = ProductSize::where(["id"=>$id]);
       if ($find->count() == 0){
           return back()->withErrors(["msg"=>"Data Tidak Ditemukan"]);
       }

        return view("produk.size.form",[
            "title"=>"Update Size",
            "data"=>$find->first(),
            "disabled"=>[],
            "route"=>route("produk.size.update.process",$id)
        ]);

    }

    public function update_process(Request $req, $id)
    {
        $req->validate([
            "name"=>"required"
        ]);
        $data = $req->all();
        unset($data["_token"]);
        $make = ProductSize::where(["id"=>$id])->update($data);
        if ($make){
            return  back()->with([
                "msg"=>"Update Ukuran Sukses",
                "url"=>route("produk.size.list")
            ]);
        }else{
            return back()->withErrors(["msg"=>"Update Ukuran Sukses"]);
        }
    }

    public function add_process(Request $req)
    {
        $req->validate([
            "name"=>"required|unique:product_sizes,name"
        ]);
        $data = $req->all();
        $make = ProductSize::create($data);
        if ($make){
            return  back()->with([
                "msg"=>"Pembuatan Ukuran Sukses",
                "url"=>route("produk.size.list")
            ]);
        }else{
            return back()->withErrors(["msg"=>"Pembuatan Ukuran Sukses"]);
        }
    }
}
