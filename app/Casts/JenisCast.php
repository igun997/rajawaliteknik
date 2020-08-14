<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class JenisCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function get($model, $key, $value, $attributes)
    {
        if ($value == 1){
            $value = "Dana Masuk";
        }elseif ($value == 2){
            $value = "Dana Keluar";
        }else{
            $value = "Tidak Diketahui";
        }
        return $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  array  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function set($model, $key, $value, $attributes)
    {
        if (is_numeric($value)){
            return $value;
        }

        if ($value == "Dana Masuk"){
            return 1;
        }elseif ($value == "Dana Keluar"){
            return 2;
        }else{
            return null;
        }
    }
}
