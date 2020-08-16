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

Route::get("/dashboard","Dashboard@index")->middleware("gateway:0|1|2")->name("dashboard");
Route::get("/akun","Akun@index")->middleware("gateway:0|1|2")->name("akun.list");
Route::get("/akun/add","Akun@akun_add")->middleware("gateway:0|1|2")->name("akun.add");
Route::post("/akun/add","Akun@akun_add_process")->middleware("gateway:0|1|2")->name("akun.add.process");
Route::get("/akun/update/{id}","Akun@akun_update")->middleware("gateway:0|1|2")->name("akun.update");
Route::post("/akun/update/{id}","Akun@akun_update_process")->middleware("gateway:0|1|2")->name("akun.update.process");
Route::get("/logout","Dashboard@logout")->middleware("gateway:0|1|2")->name("logout");


