<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Cart;
class Api extends Controller
{
    public function list_pelanggan(Request $req)
    {
        $req->validate([
            "search"=>"alphanum"
        ]);
        if (isset($req->search)){
            $pelanggan = Customer::where("name","like","%".$req->search."%")->get();
        }else{
            $pelanggan = Customer::all();
        }

        return response()->json(["data"=>$pelanggan]);

    }

    public function list_produk(Request $req)
    {
        $req->validate([
            "search"=>"alphanum"
        ]);

        if (isset($req->search)){
            $produk = Product::where("name","like","%".$req->search."%")->get();
        }else{
            $produk = Product::all();
        }

        foreach ($produk as $index => $item) {
            $item->size_data = $item->product_size()->first();
        }

        return response()->json(["data"=>$produk]);

    }

    private function _add($session,$id):void
    {
        $product = Product::findOrFail($id);
        Cart::session($session)->add(array(
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => 1,
            'attributes' => [
                "size"=>$product->product_size->first()
            ],
            'associatedModel' => $product
        ));

    }

    private function _update($session,$id,$update_data):void
    {
        Cart::session($session)->update($id,$update_data);
    }

    private function _remove($session,$id):void
    {
        Cart::session($session)->remove($id);
    }
    public function list_cart(Request $req)
    {
        $session = session()->get("id");
        $items = Cart::session($session)->getContent();
        return response()->json(["data"=>$items,"total"=>Cart::getTotal()]);
    }

    public function add_cart(Request $req)
    {
        $req->validate([
            "product_id"=>"required|exist:product,id"
        ]);

        $session = session()->get("id");
        $id = $req->product_id;
        $this->_add($session,$id);
        return response()->json(["msg"=>"Sukses Di Tambahkan"]);
    }

    public function delete_cart(Request $req)
    {
        $req->validate([
            "product_id"=>"required|exists:products ,id"
        ]);

        $session = session()->get("id");
        $id = $req->product_id;
        $this->_remove($session,$id);

        return response()->json(["msg"=>"Sukses Di Hapus"]);
    }

}
