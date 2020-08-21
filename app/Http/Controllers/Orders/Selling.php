<?php

namespace App\Http\Controllers\Orders;

use App\Casts\LevelAccount;
use App\Casts\RefType;
use App\Casts\StatusOrder;
use App\Casts\StatusTransaction;
use App\Casts\TypeTransaction;
use App\ClassesRule\CRUDInterface;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderCashbon;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Konekt\PdfInvoice\InvoicePrinter;
class InvoicePrinterOverride extends InvoicePrinter{
    public function price($price)
    {
        $decimalPoint = $this->referenceformat[0];
        $thousandSeparator = $this->referenceformat[1];
        $alignment = isset($this->referenceformat[2]) ? strtolower($this->referenceformat[2]) : 'left';
        $spaceBetweenCurrencyAndAmount = isset($this->referenceformat[3]) ? (bool) $this->referenceformat[3] : true;
        $space = $spaceBetweenCurrencyAndAmount ? ' ' : '';
        if ('right' == $alignment) {
            return number_format($price, 2, $decimalPoint, $thousandSeparator).$space.$this->currency;
        } else {
            if (is_numeric($price)){
                return $this->currency.$space.number_format($price, 2, $decimalPoint, $thousandSeparator);
            }else{
                return  "-";
            }
        }
    }
    public function forceToId()
    {
        $this->language = "ID";
        $lang['number']   = 'No Faktur';
        $lang['date']     = 'Tanggal';
        $lang['time']     = 'Waktu';
        $lang['due']      = 'Batas Waktu';
        $lang['to']       = 'Kepada YTH';
        $lang['from']     = 'Pembayaran Dari';
        $lang['product']  = 'Produk';
        $lang['qty']      = 'Jumlah';
        $lang['price']    = 'Harga';
        $lang['discount'] = 'Diskon';
        $lang['vat']      = 'Pajak';
        $lang['total']    = 'Total';
        $lang['page']     = 'Halaman';
        $lang['page_of']  = 'Dari';
        $this->lang = $lang;
    }
}
class Selling extends Controller
{

    public function index()
    {
        config::set("adminlte.sidebar_collapse",true);

        $data = Order::where(["user_id"=>session()->get("id")])->orderBy("id","desc")->get();
        if (session()->get("level") != LevelAccount::SEKRETARIS){
            $data = Order::orderBy("id","desc")->get();
        }
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
            $update = Order::where(["id"=>$req->order_id]);
            $transaction = [
                "ref_type"=>RefType::CASHBON,
                "ref_id"=>$req->order_id,
                "total"=>$data["total"],
                "descriptions"=>"Pembayaran Order Sebagian di Nota Faktur :".$update->first()->invoice_number,
                "user_id"=>session()->get("id"),
                "type"=>TypeTransaction::IN,
                "status"=>StatusTransaction::CONFIRMED,
            ];
            Transaction::create($transaction);

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

        $update = Order::where(["id"=>$req->order_id]);
        $saveUpdate = $update->update(["status"=>$req->status]);;
        if ($saveUpdate){
            if ($req->status == StatusOrder::CANCELED){
                $transaction = [
                    "ref_type"=>RefType::ORDER,
                    "ref_id"=>$req->order_id,
                    "total"=>$update->first()->total,
                    "descriptions"=>"Order Di Batalkan Dari No Faktur :".$update->first()->invoice_number,
                    "user_id"=>session()->get("id"),
                    "type"=>TypeTransaction::OUT,
                    "status"=>StatusTransaction::CONFIRMED,
                ];
                Transaction::create($transaction);
            }
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
        $find = Order::where(["id"=>$req->order_id])->first();
        $invoice = new InvoicePrinterOverride("A5","Rp. ");
        $invoice->forceToId();
        /* Header settings */
//        $invoice->setLogo("images/sample1.jpg");   //logo image path
        $invoice->setColor("#000000");      // pdf color scheme
        $invoice->setType("Nota Faktur");    // Invoice Type
        $invoice->setReference($find->invoice_number);   // Reference
        $invoice->setDate(date('d/m/Y',time()));   //Billing Date
        $invoice->setFrom([
            "Rajawali Teknik",
            "alamat perusahaan"
        ]);
        $invoice->setTo([
            $find->customer->name,
            $find->customer->address
        ]);
        foreach ($find->order_items as $row){
            $invoice->addItem($row->product()->first()->name,"Ukuran : ".$row->product()->first()->product_size()->first()->name,$row->qty,false,$row->price,false,$row->subtotal);
        }

        $invoice->addTotal("Subtotal",($find->total + $find->discount));
        $invoice->addTotal("Diskon",($find->discount*-1));
        $invoice->addTotal("Total",$find->total,false);

        $invoice->addBadge(StatusOrder::lang($find->status));
        $invoice->addTitle("Catatan Faktur");
        $invoice->addParagraph($find->additional_info);
        $invoice->render('invoice_'.time().'.pdf','D');


    }

    public function print_shipping(Request $req)
    {
        $req->validate([
            "order_id"=>"required|exists:orders,id"
        ]);
        $find = Order::where(["id"=>$req->order_id])->first();
        $invoice = new InvoicePrinterOverride("A5","Rp. ");
        $invoice->forceToId();
        /* Header settings */
//        $invoice->setLogo("images/sample1.jpg");   //logo image path
        $invoice->setColor("#000000");      // pdf color scheme
        $invoice->setType("Surat Jalan");    // Invoice Type
        $invoice->setReference($find->invoice_number);
        $invoice->setDate(date('d/m/Y',time()));   //Billing Date
        $invoice->setFrom([
            "Rajawali Teknik",
            "alamat perusahaan"
        ]);
        $invoice->setTo([
            $find->customer->name,
            $find->customer->address
        ]);
        foreach ($find->order_items as $row){
            $invoice->addItem($row->product()->first()->name,"Ukuran : ".$row->product()->first()->product_size()->first()->name,$row->qty,false,"-",false,"-");
        }


        $invoice->addTitle("Catatan ");
        $invoice->addParagraph("Nama Supir ...................");
        $invoice->addParagraph("Plat Nomor ...................");
        $invoice->addParagraph("Tanda Tangan Penerima");
        $invoice->addParagraph("");
        $invoice->addParagraph("");
        $invoice->addParagraph("...................................");
        $invoice->render('suratjalan_'.time().'.pdf','D');

    }

    public function shipping_invoice_action(Request $req)
    {
        $req->validate([
            "order_id"=>"required|exists:orders,id"
        ]);
    }
}
