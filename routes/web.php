<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get("/","Auth@index")->name("login");
Route::post("/login","Auth@login")->name("login.post");

//Modul Akun > akun.
Route::middleware("gateway:0|1|2")->prefix("akun")->name("akun.")->group(function (){
    Route::get("/","Akun@index")->name("list");
    Route::get("/add","Akun@akun_add")->name("add");
    Route::post("/add","Akun@akun_add_process")->name("add.process");
    Route::get("/update/{id}","Akun@akun_update")->name("update");
    Route::post("/update/{id}","Akun@akun_update_process")->name("update.process");
});
//Modul Pelanggan > pelanggan.
Route::middleware("gateway:1")->prefix("pelanggan")->name("pelanggan.")->group(function (){
    Route::get("/","Pelanggan@index")->name("list");
    Route::get("/add","Pelanggan@add")->name("add");
    Route::post("/add","Pelanggan@add_process")->name("add.process");
    Route::get("/update/{id}","Pelanggan@update")->name("update");
    Route::post("/update/{id}","Pelanggan@update_process")->name("update.process");
});
//Modul Produk > produk.
Route::middleware("gateway:1")->prefix("produk")->namespace("Produk")->name("produk.")->group(function (){
    Route::get("/","Produk@index")->name("list");
    Route::get("/add","Produk@add")->name("add");
    Route::post("/add","Produk@add_process")->name("add.process");
    Route::get("/update/{id}","Produk@update")->name("update");
    Route::post("/update/{id}","Produk@update_process")->name("update.process");
    //Sub Module Size >  produk.util
    Route::prefix("size")->name("size.")->group(function (){
        Route::get("/","Size@index")->name("list");
        Route::get("/add","Size@add")->name("add");
        Route::post("/add","Size@add_process")->name("add.process");
        Route::get("/update/{id}","Size@update")->name("update");
        Route::post("/update/{id}","Size@update_process")->name("update.process");
    });
});
//Modul Orders > orders.
Route::middleware("gateway:1")->prefix("orders")->namespace("Orders")->name("orders.")->group(function (){
    Route::get("/","Selling@index")->name("list");
    Route::get("/update_status","Selling@update_status")->name("update_status");

    Route::middleware("gateway:1")->prefix("print")->name("print.")->group(function (){
        Route::get("invoice","Selling@print_invoice")->name("faktur");
        Route::get("invoice/action","Selling@print_invoice_action")->name("faktur.action");
        Route::get("shipping","Selling@print_shipping")->name("shipping");
        Route::get("shipping/action","Selling@print_shipping_action")->name("shipping.action");
    });
    Route::middleware("gateway:1")->prefix("api")->name("api.")->group(function (){
        Route::get("product","Selling@product_detail")->name("product");
        Route::get("cashbon","Selling@cashbon_detail")->name("cashbon");
        Route::post("cashbon","Selling@cashbon_create")->name("cashbon.create");
    });

});
//Modul POS > pos.
Route::middleware("gateway:1")->prefix("pos")->namespace("POS")->name("pos.")->group(function (){
    Route::get("/","Main@index")->name("menus");
    //Sub Module Size >  produk.util
    Route::prefix("api")->name("api.")->group(function (){
        Route::get("/pelanggan","Api@list_pelanggan")->name("pelanggan");
        Route::get("/produk","Api@list_produk")->name("produk");

        Route::get("/cart","Api@list_cart")->name("cart.list");
        Route::post("/cart/add","Api@add_cart")->name("cart.add");
        Route::post("/cart/discount","Api@discount_cart")->name("cart.discount");
        Route::get("/cart/clear","Api@clear_cart")->name("cart.clear");
        Route::post("/cart/delete","Api@delete_cart")->name("cart.delete");
        Route::post("/cart/checkout","Api@checkout_cart")->name("cart.checkout");
    });
});
//Modul Laporan
Route::middleware("gateway:0|1|2")->prefix("laporan")->namespace("Laporan")->name("laporan.")->group(function (){
    Route::get("/","Main@index")->name("menus");
    //Sub Module Laporan >  laporan.keuangan
    Route::prefix("keuangan")->middleware("gateway:2")->name("keuangan.")->group(function (){

    });
    //Sub Module Laporan >  laporan.penjualan
    Route::prefix("penjualan")->middleware("gateway:1")->name("penjualan.")->group(function (){

    });
    //Sub Module Laporan >  laporan.pelanggan
    Route::prefix("pelanggan")->middleware("gateway:1")->name("pelanggan.")->group(function (){

    });
});


Route::get("/dashboard","Dashboard@index")->middleware("gateway:0|1|2")->name("dashboard");
Route::get("/logout","Dashboard@logout")->middleware("gateway:0|1|2")->name("logout");


