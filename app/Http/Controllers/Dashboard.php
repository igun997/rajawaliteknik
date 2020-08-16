<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Dashboard extends Controller
{
    public function index()
    {
        return view("dashboard.home",[
            "title"=>"Dashboard"
        ]);
    }

    public function logout()
    {
        session()->flush();
        return redirect(route("login"));
    }
}
