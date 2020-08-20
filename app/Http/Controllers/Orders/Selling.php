<?php

namespace App\Http\Controllers\Orders;

use App\Casts\StatusOrder;
use App\ClassesRule\CRUDInterface;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderCashbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class Selling extends Controller
{

    public function index()
    {
        config::set("adminlte.sidebar_collapse",true);
        $data = Order::where(["user_id"=>session()->get("id")])->orderBy("id","desc")->get();
        foreach ($data as $index => $datum) {
            $datum->status_lang = StatusOrder::lang($datum->status);
        }
        return view("orders.home",[
            "title"=>"Data Penjualan",
            "data"=>$data
        ]);
    }

    public function product_detail(Request $req)
    {
        $req->validate([
            "order_id"=>"required|exists:orders,id"
        ]);
        $find = Order::where(["id"=>$req->order_id])->first();
        $items = $find->order_items()->get();
        foreach ($items as $index => $item) {
            $item->product_name = $item->product->name;
            $item->size_name = $item->product->product_size->name;
        }
        $find->status_lang = StatusOrder::lang($find->status);
        $find->cashbon_status = ($find->status === StatusOrder::CASHBON);
        $find->cashbon_progress = $find->order_cashbons->sum("total");
        $find->items = $items;
        return response()->json(["data"=>$find]);
    }

    public function cashbon_detail(Request $req)
    {
        $req->validate([
            "order_id"=>"required|exists:orders,id"
        ]);

        $find = Order::where(["id"=>$req->order_id])->first();
        $find->paided_total = $find->order_cashbons->sum("total");

        return response()->json(["data"=>$find]);
    }

    public function cashbon_create(Request $req)
    {
        $req->validate([
            "order_id"=>"required|exists:orders,id",
            "total"=>"required|numeric|gt:0",
        ]);
        $data = $req->all();
        $data["user_id"] = session()->get("id");

        $make = OrderCashbon::create($data);
        if ($make){
            if ($req->has("paided")){
                Order::where(["id"=>$req->order_id])->update(["status"=>StatusOrder::PAYMENT_CONFIRMED]);
                return response()->json(["msg"=>"Sukses Input Cashbon","reload"=>true]);

            }else{
                return response()->json(["msg"=>"Sukses Input Cashbon","reload"=>false]);

            }
        }else{
            return response()->json(["msg"=>"Gagal Input Cashbon"]);
        }
    }

    public function update_status(Request $req)
    {
        $req->validate([
            "order_id"=>"required|exists:orders,id",
            "status"=>"required"
        ]);

        $update = Order::where(["id"=>$req->order_id])->update(["status"=>$req->status]);
        if ($update){
            return response()->json(["msg"=>"Status Order Berubah : ".StatusOrder::lang($req->status)]);
        }else{
            return response()->json(["msg"=>"Status Gagal Di Ubah : ".StatusOrder::lang($req->status)]);

        }
    }

    public function print_invoice(Request $req)
    {
        $req->validate([
            "order_id"=>"required|exists:orders,id"
        ]);
    }

    public function print_invoice_action(Request $req)
    {
        $req->validate([
            "order_id"=>"required|exists:orders,id"
        ]);
    }
    public function shipping_invoice(Request $req)
    {
        $req->validate([
            "order_id"=>"required|exists:orders,id"
        ]);
    }

    public function shipping_invoice_action(Request $req)
    {
        $req->validate([
            "order_id"=>"required|exists:orders,id"
        ]);
    }
}
