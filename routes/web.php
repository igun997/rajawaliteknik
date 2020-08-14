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

Route::get("/",function (){
   return redirect(route("front.home"));
});

Route::prefix("/front")->namespace("Front")->group(function (){
    Route::get("/","Home@index")->name("front.home");
    Route::get("/tentang","Home@tentang")->name("front.tentang");
    Route::get("/detail/{id}","Home@kegiatan_detail")->name("front.kegiatan");
});

Route::get("/login","Auth@page_login")->middleware("gateway");
Route::post("/login","Auth@login")->middleware("gateway")->name("login");

Route::get("/register","Auth@page_register")->middleware("gateway");
Route::post("/register","Auth@register")->middleware("gateway")->name("register");

Route::get("/logout","Auth@logout")->middleware("gateway");

Route::prefix("donatur")->namespace("Donatur")->middleware("gateway:0")->group(function (){
    Route::get("/","Pages@home");
    Route::get("/donasi","Pages@donasi")->name("donasi");
    Route::get("/donasi/upload/{id}","Pages@donasi_upload")->name("upload.bukti");
    Route::post("/donasi/upload/{id}","System@donasi_upload")->name("donasi.upload.update");
    Route::get("/donasi/add","Pages@donasi_add")->name("donasi.add");
    Route::post("/donasi/add","System@donasi_insert")->name("donasi.add.insert");
    Route::get("/laporan","Pages@laporan");
    Route::post("/laporan","\App\Http\Controllers\Bendahara\System@laporan")->name("laporan.donatur.generate.donatur");
});

Route::prefix("sekretaris")->namespace("Sekretaris")->middleware("gateway:1")->group(function (){
    Route::get("/","Pages@home");
    Route::get("/kegiatan","Pages@kegiatan");
    Route::get("/kegiatan/verifikasi/{id}/{status}","System@kegiatan_verifikasi")->name("sekretaris.kegiatan.verifikasi");

    Route::get("/laporan","Pages@laporan")->name("laporan");
    Route::post("/laporan/donatur","System@cetak_donatur")->name("laporan.donatur.sekretaris");


});

Route::prefix("bendahara")->namespace("Bendahara")->middleware("gateway:2")->group(function (){

    Route::get("/","Pages@home");
    Route::get("/donasi/minus","Pages@donatur_minus")->name("donasi.donatur.minus");
    Route::post("/donasi/minus","System@donatur_minus")->name("donatur.insert.minus");
    Route::get("/operasional","Pages@operasional")->name("operasional.donatur");
    Route::get("/laporan","Pages@laporan");
    Route::post("/laporan","System@laporan")->name("laporan.donatur.generate");

});

Route::prefix("ketua")->namespace("Ketua")->middleware("gateway:3")->group(function (){
    Route::get("/","Pages@home");
    Route::get("/kegiatan","Pages@kegiatan");
    Route::get("/kegiatan/verifikasi/{id}/{status}","System@kegiatan_verifikasi")->name("ketua.kegiatan.verifikasi");
    Route::get("/laporan/donatur","Pages@lap_donatur");
    Route::post("/laporan/donatur","System@lap_donatur")->name("ketua.lap.donatur");
    Route::get("/laporan/keuangan","Pages@lap_keuangan");
    Route::post("/laporan/keuangan","System@lap_keuangan")->name("ketua.lap.keuangan");
});
Route::prefix("pelayanan")->namespace("Pelayanan")->middleware("gateway:4")->group(function (){
    Route::get("/","Pages@home");
    Route::get("/donatur","Pages@donatur")->name("sekretaris.donatur");
    Route::get("/donatur/verifikasi/{id}/{status}","System@donatur_verifikasi")->name("sekretaris.donatur.verifikasi");

    Route::get("/category","Pages@category")->name("category.list");
    Route::get("/category/update/{id}","Pages@category_update")->name("category.page.update");
    Route::get("/category/add","Pages@category_add")->name("category.insert.page");
    Route::get("/category/delete/{id}","System@category_delete")->name("category.delete");
    Route::post("/category/update/{id}","System@category_update")->name("category.update");
    Route::post("/category/add","System@category_insert")->name("category.insert");

    Route::get("/kegiatan","Pages@kegiatan")->name("kegiatan.list");
    Route::get("/kegiatan/detail/{id}","Pages@kegiatan_detail")->name("kegiatan.detail");
    Route::get("/kegiatan/add","Pages@kegiatan_add")->name("kegiatan.add");
    Route::get("/kegiatan/add/partisipan/{id}","Pages@kegiatan_add_partisipan")->name("kegiatan.add.partisipan");
    Route::post("/kegiatan/add/partisipan/{id?}","System@kegiatan_insert_partisipan")->name("kegiatan.insert.partisipan");
    Route::get("/kegiatan/partisipan/delete/{id}","System@kegiatan_delete_partisipan")->name("kegiatan.partisipan.delete");
    Route::get("/kegiatan/partisipan/cetak/{id}","System@cetak_absensi")->name("kegiatan.partisipan.cetak");
    Route::post("/kegiatan/add","System@kegiatan_insert")->name("kegiatan.insert");
    Route::get("/kegiatan/update/{id}","Pages@kegiatan_update")->name("kegiatan.page.update");
    Route::post("/kegiatan/update/{id}","System@kegiatan_update")->name("kegiatan.update");
    Route::get("/kegiatan/delete/{id}","System@kegiatan_delete")->name("kegiatan.delete");
});
Route::prefix("pengabdian")->namespace("Pengabdian")->middleware("gateway:5")->group(function (){
    Route::get("/","Pages@home");
    Route::get("/donasi/plus","Pages@donatur_plus")->name("donasi.donatur.plus");
    Route::get("/donasi","Pages@donatur")->name("donasi.donatur");
    Route::post("/donasi/plus","System@donatur_plus")->name("donatur.insert.plus");
    Route::get("/donasi/verifikasi/{id}/{status}","System@donasi_verifikasi")->name("donasi.donatur.verifikasi");


});
