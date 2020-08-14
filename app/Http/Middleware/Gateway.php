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
        if ($level === NULL || $is_must === NULL){
            Event::listen("JeroenNoten\LaravelAdminLte\Events\BuildingMenu",function ($e){
                $e->menu->add([
                   "text"=>"Login",
                    "url"=>"login",
                    "icon"=>"fa fa-sign-in-alt"
                ]);

                $e->menu->add([
                    "text"=>"Register",
                    "url"=>"register",
                    "icon"=>"fa fa-users"
                ]);
            });
            return $next($request);
        }else{


            if ($level == $is_must){
                return $next($request);
            }else{
                return  redirect("/login")->withErrors(["msg"=>"Anda tidak memiliki akses ke halaman ini"]);
            }
        }

    }
}
