<?php

namespace App\Http\Controllers\POS;

use App\Casts\RefType;
use App\Casts\StatusOrder;
use App\Casts\StatusTransaction;
use App\Casts\TypeTransaction;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;
use Darryldecode\Cart\CartCondition;
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
        return response()->json(["data"=>$items,"total"=>Cart::session($session)->getTotal(),"discount"=>Cart::session($session)->getConditions()]);
    }

    public function add_cart(Request $req)
    {
        $req->validate([
            "product_id"=>"required|exists:products,id",
        ]);

        $session = session()->get("id");
        $id = $req->product_id;
        $this->_add($session,$id);
        return response()->json(["msg"=>"Sukses Di Tambahkan"]);
    }

    public function clear_cart()
    {
        Cart::session(session()->get("id"))->clear();
        Cart::session(session()->get("id"))->clearCartConditions();

        return response()->json(["msg"=>"Cart Di Reset"]);
    }

    public function discount_cart(Request $req){
        $req->validate([
            "customer_id"=>"required|exists:customers,id",
        ]);
        $find = Customer::where(["id"=>$req->customer_id,"has_discount"=>1]);
        if ($find->count() > 0){
            $have = $find->first()->percentage_discount;
            $condition = new CartCondition([
                'name' => 'Diskon Pelanggan '.$have.'%',
                'type' => 'promo',
                'target' => 'total',
                'value' => ($have*-1)."%",
                'attributes' =>[]
            ]);
            Cart::session(session()->get("id"))->condition($condition);
            return response()->json(["msg"=>'Diskon Pelanggan '.$have.'% Terapkan']);
        }else{
            return response()->json(["msg"=>"Tidak Memiliki Diskon"]);
        }

    }

    public function delete_cart(Request $req)
    {
        $req->validate([
            "product_id"=>"required|exists:products,id"
        ]);

        $session = session()->get("id");
        $id = $req->product_id;
        $this->_remove($session,$id);

        return response()->json(["msg"=>"Sukses Di Hapus"]);
    }

    public function checkout_cart(Request $req)
    {
        $req->validate([
            "customer_id"=>"required|exists:customers,id",
        ]);
        $data = $req->all();
        $status = StatusOrder::CASHBON;
        $user_id = session()->get("id");
        $info = "Pembayaran Belum Lunas";
        if ($data["additional_info"][0] !== "Cashbon"){
            $info = "Pembayaran Lunas Dengan Metode Pembayaran ".$data["additional_info"][0];
            $status = StatusOrder::PAYMENT_CONFIRMED;

        }
        unset($data["additional_info"]);
        $data["user_id"] = $user_id;
        $data["status"] = $status;
        $data["additional_info"] = $info;
        $data["total"] = str_replace(",","",Cart::session($user_id)->getTotal());
        $diskons = Cart::session($user_id)->getConditions();
        foreach ($diskons as $index => $diskon) {
            $data["discount"] = $diskon->parsedRawValue;
        }
        $num = Order::whereDate('created_at', Carbon::today())->count() + 1;
        $data["invoice_number"] = "ORD/".date("d/m/Y")."/".str_pad($num,5,0,STR_PAD_LEFT);
        $makeOrder = Order::create($data);
        if ($makeOrder){
            $ord_id = $makeOrder->id;
            $lists = Cart::session($user_id)->getContent();
            $makeItems = false;
            foreach ($lists as $index => $list) {
                $product = [
                    "order_id"=>$ord_id,
                    "product_id"=>$index,
                    "qty"=>$list->quantity,
                    "price"=>$list->price,
                    "subtotal"=>($list->quantity*$list->price),
                ];
                $makeItems = OrderItem::create($product);
            }
            if ($makeItems){
                if ($status == StatusOrder::PAYMENT_CONFIRMED){
                    $transaction = [
                        "ref_type"=>RefType::ORDER,
                        "ref_id"=>$ord_id,
                        "total"=>$data["total"],
                        "descriptions"=>"Dana Dari No Faktur :".$data["invoice_number"],
                        "user_id"=>$user_id,
                        "type"=>TypeTransaction::IN,
                        "status"=>StatusTransaction::CONFIRMED,
                    ];
                    Transaction::create($transaction);
                }
                Cart::session($user_id)->clear();
                Cart::session($user_id)->clearCartConditions();
                return  response()->json(["msg"=>"Order Telah Di Selesaikan","url"=>route("orders.print.faktur")."?order_id=$ord_id"]);
            }else{
                return Order::where(["id"=>$ord_id])->delete();
            }
        }

        return  response()->json(["msg"=>"Gagal Checkout Cart"]);


    }

}
