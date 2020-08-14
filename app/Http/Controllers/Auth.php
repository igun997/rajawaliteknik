<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class Auth extends Controller
{

    public function page_home(){
        return view("home_front",[
            "title"=>"Selamat Datang "
        ]);
    }
    public function page_login(){
        return view("login",[
            "title"=>"Halaman Login",
        ]);
    }

    public function page_register(){
        return view("register",[
            "title"=>"Halaman Register",
        ]);
    }
    public function register(Request $req){
        $req->validate([
            "nama"=>"required",
            "username"=>"required|unique:users,username",
            "password"=>"required",
            "email"=>"required|unique:users,email",
            "no_hp"=>"required|unique:users,no_hp",
        ]);

        $level = 0;
        $created = date("Y-m-d");

        $data = $req->all();
        $data["level"] = $level;
        $data["status"] = 0;
        $data["created_at"] = $created;
        $users = User::create($data);

        if ($users){
            return back()->with(["msg"=>"Sukses Simpan , Silahkan Cek Email Anda "]);
        }else{
            return back()->withErrors(["msg"=>"Error Server"]);
        }

    }

    public function logout(){
        session()->flush();
        return redirect("/");
    }

    public function login(Request $req){
        $req->validate([
           "username"=>"required|exists:users,username",
           "password"=>"required|exists:users,password",
        ]);

        $find = User::where(["username" => $req->username,"password" => $req->password,"status"=>1]);
        if ($find->count() > 0){
            session([
               "level"=>$find->first()->level,
               "nama"=>$find->first()->nama,
               "id"=>$find->first()->id,
            ]);
            if ($find->first()->level == 0){
                $pages = "donatur";
            }elseif ($find->first()->level == 1){
                $pages = "sekretaris";

            }elseif ($find->first()->level == 2){
                $pages = "bendahara";
            }elseif ($find->first()->level == 3){
                $pages = "ketua";
            }elseif ($find->first()->level == 4){
                $pages = "pelayanan";
            }elseif ($find->first()->level == 5){
                $pages = "pengabdian";
            }
            return redirect("/".$pages)->with(["msg"=>"Sukses Login"]);
        }else{
            return back()->withErrors(["msg"=>"Data Pengguna Tidak Ditemukan / Belum Di Verifikasi"]);
        }

    }
}
