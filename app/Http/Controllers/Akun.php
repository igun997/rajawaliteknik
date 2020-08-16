<?php

namespace App\Http\Controllers;

use App\Casts\LevelAccount;
use App\Casts\StatusAccount;
use App\Models\User;
use Illuminate\Http\Request;

class Akun extends Controller
{
    public function index()
    {
        $level = session()->get("level");
        $data = User::where(["level"=>$level,"sub_level"=>1])->get();
        foreach ($data as $index => &$datum) {
            $datum->level_lang = LevelAccount::lang($level);
            $datum->status_lang = StatusAccount::lang($datum->status);
        }
        return view("akun.home",[
            "title"=>"Data Akun ".LevelAccount::lang($level),
            "data"=>$data
        ]);
    }

    public function akun_add()
    {
        return view("akun.form",[
            "title"=>"Tambah Akun ",
            "data"=>[],
            "disabled"=>[],
            "route"=>route("akun.add.process")
        ]);
    }
    public function akun_update($id)
    {
        $find = User::where(["id"=>$id,"sub_level"=>1]);
        if ($find->count() === 0){
            return back()->withErrors(["msg"=>"Akun Tidak Ditemukan"]);
        }

        return view("akun.form",[
            "title"=>"Update Akun ",
            "data"=>$find->first(),
            "disabled"=>["username"],
            "route"=>route("akun.update.process",$id)
        ]);
    }

    public function akun_update_process(Request $req,$id)
    {
        $req->validate([
            "password"=>"required",
            "name"=>"required",
            "email"=>"required"
        ]);
        $data = $req->all();
        $data["level"] = session()->get("level");
        $data["status"] = StatusAccount::ACTIVE;
        $data["sub_level"] = 1;
        unset($data["_token"]);

        $make = User::where(["id"=>$id])->update($data);
        if ($make){
            return  back()->with([
                "msg"=>"Update Sub-Akun Sukses",
                "url"=>route("akun.list")
            ]);
        }else{
            return back()->withErrors(["msg"=>"Update Sub-Akun Gagal"]);
        }
    }
    public function akun_add_process(Request $req)
    {
        $req->validate([
            "username"=>"required|unique:users,username",
            "password"=>"required",
            "name"=>"required",
            "email"=>"required"
        ]);
        $data = $req->all();
        $data["level"] = session()->get("level");
        $data["status"] = StatusAccount::ACTIVE;
        $data["sub_level"] = 1;

        $make = User::create($data);
        if ($make){
            return  back()->with([
                "msg"=>"Pembuatan Sub-Akun Sukses",
                "url"=>route("akun.list")
            ]);
        }else{
            return back()->withErrors(["msg"=>"Pembuatan Sub-Akun Gagal"]);
        }

    }
}
