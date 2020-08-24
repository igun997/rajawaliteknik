<?php

namespace App\Http\Controllers\Pelanggan;

use App\ClassesRule\CRUDInterface;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerDiscount;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

class Discount extends Controller implements CRUDInterface
{
    public function index()
    {
        $req = Request::capture();
        $pelanggan = CustomerDiscount::where(["customer_id"=>$req->id])->get();
        return view("pelanggan.discount.home",[
            "title"=>"Diskon Pelanggan",
            "data"=>$pelanggan,
            "id"=>$req->id
        ]);
    }

    public function add()
    {
        $req = Request::capture();
        return view("pelanggan.discount.form",[
            "title"=>"Tambah Diskon",
            "route"=>route("pelanggan.discount.add.process",["id"=>$req->id])
        ]);
    }

    public function update($id)
    {
        $find = CustomerDiscount::findOrFail($id);
        return view("pelanggan.discount.form",[
            "title"=>"Update Diskon",
            "data"=>$find,
            "route"=>route("pelanggan.discount.update.process",$id)
        ]);
    }

    public function update_process(Request $req, $id)
    {
        $req->validate([
            "product_id"=>"required|exists:products,id",
            "percentage_discount"=>"required|numeric|gte:0|lte:100"
        ]);

        $data = $req->all();
        unset($data["_token"]);
        $update = CustomerDiscount::where(["id"=>$id]);
        $updateUp = $update->update($data);
        if ($update){
            return back()->with(["msg"=>"Diskon Telah Di Update","url"=>route("pelanggan.discount.list",["id"=>$update->first()->customer_id])]);
        }
        return back()->withErrors(["msg"=>"Update Diskon Gagal"]);
    }

    public function add_process(Request $req)
    {
        $req->validate([
            "product_id"=>"required|exists:products,id",
            "id"=>"required|exists:customers,id",
            "percentage_discount"=>"required|numeric|gte:0|lte:100"
        ]);

        $data = $req->all();
        $data['customer_id'] = $data["id"];
        $update = CustomerDiscount::create($data);
        if ($update){
            return back()->with(["msg"=>"Diskon Telah Di Buat","url"=>route("pelanggan.discount.list",["id"=>$update->customer_id])]);
        }
        return back()->withErrors(["msg"=>"Buat Diskon Gagal"]);
    }
}
