<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class RefType implements CastsAttributes
{

    const ORDER = 0;
    const PURCHASE = 1;
    const CASHBON = 2;
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
        if ($type == RefType::ORDER){
            return "Order";
        }elseif ($type == RefType::PURCHASE){
            return "Purchase Order";
        }elseif ($type == RefType::CASHBON){
            return "Cashbon";
        }else{
            return  FALSE;
        }
    }
}
