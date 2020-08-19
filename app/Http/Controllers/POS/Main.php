<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper;
use Illuminate\Http\Request;

class Main extends Controller
{
    public function index()
    {
        config::set("adminlte.sidebar_collapse",true);
        return view("pos.main",[
            "title"=>"POS",
            "staff"=>session()->get("name"),
            "id"=>session()->get("id")
        ]);
    }
}
