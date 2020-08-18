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

//Modul Akun
Route::middleware("gateway:0|1|2")->prefix("akun")->name("akun.")->group(function (){
    Route::get("/","Akun@index")->name("list");
    Route::get("/add","Akun@akun_add")->name("add");
    Route::post("/add","Akun@akun_add_process")->name("add.process");
    Route::get("/update/{id}","Akun@akun_update")->name("update");
    Route::post("/update/{id}","Akun@akun_update_process")->name("update.process");
});

Route::middleware("gateway:1")->prefix("pelanggan")->name("pelanggan.")->group(function (){
    Route::get("/","Pelanggan@index")->name("list");
    Route::get("/add","Pelanggan@add")->name("add");
    Route::post("/add","Pelanggan@add_process")->name("add.process");
    Route::get("/update/{id}","Pelanggan@update")->name("update");
    Route::post("/update/{id}","Pelanggan@update_process")->name("update.process");
});


Route::get("/dashboard","Dashboard@index")->middleware("gateway:0|1|2")->name("dashboard");
Route::get("/logout","Dashboard@logout")->middleware("gateway:0|1|2")->name("logout");


