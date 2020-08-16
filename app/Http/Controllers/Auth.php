<?php

namespace App\Http\Controllers;

use App\Casts\LevelAccount;
use App\Casts\StatusAccount;
use App\Models\User;
use Illuminate\Http\Request;

class Auth extends Controller
{

    public function index(){
        return view("login");
    }

    public function login(Request $req)
    {
        $req->validate([
            "email"=>"required",
            "password"=>"required"
        ]);

        $cek = User::where(["email"=>$req->email,"password"=>$req->password,"status"=>StatusAccount::ACTIVE]);
        if ($cek->count() > 0){
            $build = [
                "name"=>$cek->first()->name,
                "level"=>$cek->first()->level,
                "username"=>$cek->first()->username,
                "url"=>LevelAccount::redirect($cek->first()->level),
            ];
            session($build);
            return redirect($build["url"])->with(["msg"=>"Selamat Datang ".$build["name"]]);
        }else{
            return back()->withErrors(["msg"=>"Username & Password Salah"]);
        }
    }
}
