<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Event;

class Gateway
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$is_must = null)
    {
        $level = session()->get("level");
        $sub_level = (session()->get("sub_level") == 1);
        if ($level === NULL || $is_must === NULL){
            return  redirect("/")->withErrors(["msg"=>"Anda Belum Login"]);

        }else{
            $exploded = explode("|",$is_must);
            if (in_array($level,$exploded)){
                $is_must = $level;
                Event::listen("JeroenNoten\LaravelAdminLte\Events\BuildingMenu",function ($e){
                    $e->menu->add([
                        "text"=>"Beranda",
                        "url"=>"dashboard",
                        "icon"=>"fa fa-home"
                    ]);
                });

                if ($level == 0){
                    Event::listen("JeroenNoten\LaravelAdminLte\Events\BuildingMenu",function ($e){
                        $e->menu->add([
                            "text"=>"Laporan",
                            "url"=>"laporan",
                            "icon"=>"fa fa-file"
                        ]);
                    });
                }elseif ($level == 1){
                    Event::listen("JeroenNoten\LaravelAdminLte\Events\BuildingMenu",function ($e){
                        $e->menu->add([
                            "text"=>"Pelanggan",
                            "url"=>"pelanggan",
                            "icon"=>"fa fa-file"
                        ]);
                        $e->menu->add([
                            "text"=>"POS",
                            "url"=>"pos",
                            "icon"=>"fa fa-file"
                        ]);
                        $e->menu->add([
                            "text"=>"Produk",
                            "url"=>"produk",
                            "icon"=>"fa fa-file"
                        ]);
                        $e->menu->add([
                            "text"=>"Laporan",
                            "url"=>"laporan",
                            "icon"=>"fa fa-file"
                        ]);
                    });
                }elseif ($level == 2){
                    Event::listen("JeroenNoten\LaravelAdminLte\Events\BuildingMenu",function ($e){

                        $e->menu->add([
                            "text"=>"Penjualan",
                            "url"=>"penjualan",
                            "icon"=>"fa fa-file"
                        ]);

                        $e->menu->add([
                            "text"=>"Transaksi Keuangan",
                            "url"=>"keuangan",
                            "icon"=>"fa fa-file"
                        ]);

                        $e->menu->add([
                            "text"=>"Laporan",
                            "url"=>"laporan",
                            "icon"=>"fa fa-file"
                        ]);

                    });
                }
                if (!$sub_level){
                    Event::listen("JeroenNoten\LaravelAdminLte\Events\BuildingMenu",function ($e){
                        $e->menu->add([
                            "text"=>"Akun",
                            "url"=>"akun",
                            "icon"=>"fa fa-users"
                        ]);
                    });
                }
                Event::listen("JeroenNoten\LaravelAdminLte\Events\BuildingMenu",function ($e){
                    $e->menu->add([
                        "text"=>"Logout",
                        "url"=>"logout",
                        "icon"=>"fa fa-sign-out-alt"
                    ]);

                });
            }

            if ($level == $is_must){
                return $next($request);
            }else{
                return  redirect("/")->withErrors(["msg"=>"Anda tidak memiliki akses ke halaman ini "]);
            }
        }

    }
}
