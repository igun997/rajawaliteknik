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
Route::get("/logout","Dashboard@logout")->middleware("gateway:0|1|2")->name("logout");


