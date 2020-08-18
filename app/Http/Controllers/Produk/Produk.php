<?php

namespace App\Http\Controllers\Produk;

use App\Casts\StatusProduct;
use App\ClassesRule\CRUDInterface;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Http\Request;

class Produk extends Controller implements CRUDInterface
{
    //
    public function index()
    {
        $data = Product::orderBy("created_at","desc")->get();
        foreach ($data as $index => &$datum) {
            $datum->status_lang = StatusProduct::lang($datum->status);
        }
        return view("produk.home",[
            "title"=>"Data Produk",
            "data"=>$data
        ]);
    }

    public function add()
    {
        $sizes = ProductSize::all();
        if (count($sizes) === 0){
            return redirect(route("produk.size.add"))->withErrors(["msg"=>"Silahkan Isi Ukuran Terlebih Dahulu"]);
        }
        return view("produk.form",[
            "title"=>"Tambah Produk",
            "data"=>[],
            "sizes"=>$sizes,
            "disabled"=>[],
            "route"=>route("produk.add.process")
        ]);
    }

    public function update($id)
    {
        $find = Product::where(["id"=>$id]);
        if ($find->count() == 0){
            return back()->withErrors(["msg"=>"Data Tidak Ditemukan"]);
        }
        $sizes = ProductSize::all();
        return view("produk.form",[
            "title"=>"Update Produk",
            "data"=>$find->first(),
            "sizes"=>$sizes,
            "disabled"=>[],
            "route"=>route("produk.update.process",$id)
        ]);

    }

    public function update_process(Request $req, $id)
    {
        $req->validate([
            "name"=>"required",
            "size_id"=>"required|exists:product_sizes,id",
            "stock"=>"numeric",
            "price"=>"numeric|required"
        ]);
        $data = $req->all();
        unset($data["_token"]);
        $data["status"] = StatusProduct::ACTIVE;
        $data["user_id"] = session()->get("id");

        $make = Product::where(["id"=>$id])->update($data);
        if ($make){
            return back()->with(["msg"=>"Produk Berhasil Di Tambahkan","url"=>route("produk.list")]);
        }else{
            return back()->with(["msg"=>"Produk Gagal Di Tambahkan"]);
        }
    }

    public function add_process(Request $req)
    {
        $req->validate([
            "name"=>"required",
            "size_id"=>"required|exists:product_sizes,id",
            "stock"=>"numeric",
            "price"=>"numeric|required"
        ]);
        $data = $req->all();
        $data["status"] = StatusProduct::ACTIVE;
        $data["user_id"] = session()->get("id");

        $make = Product::create($data);
        if ($make){
            return back()->with(["msg"=>"Produk Berhasil Di Tambahkan","url"=>route("produk.list")]);
        }else{
            return back()->with(["msg"=>"Produk Gagal Di Tambahkan"]);
        }
    }
}
