<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class UserStatus implements CastsAttributes
{

    public function set($model, $key, $value, $attributes)
    {
            if (is_numeric($value)){
                return $value;
            }

        if ($value == "Menunggu"){
            return 0;
        }elseif ($value == "Terverifikasi"){
            return 1;
        }elseif ($value == "Ditolak"){
            return 2;
        }else{
            return null;
        }
    }

    public function get($model, string $key, $value, array $attributes)
    {
        if ($value == 0){
            $value = "Menunggu";
        }elseif ($value == 1){
            $value = "Terverifikasi";
        }elseif ($value == 2){
            $value = "Ditolak";
        }else{
            $value = "Tidak Diketahui";
        }
        return $value;
    }
}
