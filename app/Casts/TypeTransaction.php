<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class TypeTransaction implements CastsAttributes
{
    const IN = 1;
    const OUT = 0;
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
        if ($type == TypeTransaction::IN){
            return "Dana Masuk";
        }elseif ($type == TypeTransaction::OUT){
            return "Dana Keluar";
        }else{
            return false;
        }
    }
}
