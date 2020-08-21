<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class Dashboard extends Controller
{
    public function index()
    {
        $date = [];
        $data = [];
        $start = date("Y-m-d",strtotime("-3 days",time()));
        $end = date("Y-m-d",strtotime("+3 days",time()));
        $period = CarbonPeriod::create($start, $end);
        $seed = Order::whereBetween('created_at', [$start, $end])->groupBy("created_at")->orderBy("created_at","asc")->selectRaw("SUM(total) as total_day,created_at")->get();
        $dates = $period->toArray();
        foreach ($dates as $index => $date_data) {
            $date[] =   '"'.date("d/m",strtotime($date_data)).'"';
            $zero = true;
            foreach ($seed as $s) {
                if ((date("d/m/y",strtotime($date_data))) == date("d/m/y",strtotime($s->created_at))){
                    $zero = false;
                    $data[] = $s->total_day;
                }
            }
            if ($zero){
                $data[] = 0;
            }
        }
        return view("dashboard.home",[
            "title"=>"Dashboard",
            "date"=>implode(",",$date),
            "data"=>implode(",",$data)
        ]);
    }

    public function logout()
    {
        session()->flush();
        return redirect(route("login"));
    }
}
