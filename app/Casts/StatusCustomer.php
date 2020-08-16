<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class StatusCustomer implements CastsAttributes
{
    const ACTIVE = 1;
    const DISABLED = 0;
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
        return $value;
    }
    public static function lang($type)
    {
        if ($type == StatusCustomer::ACTIVE){
            return "Aktif";
        }elseif ($type == StatusCustomer::DISABLED){
            return "Tidak Aktif";
        }else{
            return  FALSE;
        }
    }
}
