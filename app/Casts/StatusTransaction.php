<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class StatusTransaction implements CastsAttributes
{
    const WAITING_CONFIRMATION = 0;
    const CONFIRMED = 1;
    const REJECTED = 2;
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
        if ($type == StatusTransaction::WAITING_CONFIRMATION){
            return "Menunggu Konfirmasi";
        }elseif ($type == StatusTransaction::CONFIRMED){
            return "Dikonfirmasi";
        }elseif ($type == StatusTransaction::REJECTED){
            return "Ditolak";
        }else{
            return false;
        }
    }
}
