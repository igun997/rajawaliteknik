<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class LevelAccount implements CastsAttributes
{
    const  DIREKTUR = 0;
    const  SEKRETARIS = 1;
    const  BENDAHARA = 2;
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

    public static function lang($level)
    {
        if ($level == LevelAccount::SEKRETARIS){
            return "Sekretaris";
        }elseif ($level == LevelAccount::DIREKTUR){
            return "Direktur";
        }elseif ($level == LevelAccount::BENDAHARA){
            return "Bendahara";
        }else{
            return  FALSE;
        }
    }

    public static function redirect($level)
    {
        if ($level == LevelAccount::SEKRETARIS){
            return route("dashboard");
        }elseif ($level == LevelAccount::DIREKTUR){
            return route("dashboard");
        }elseif ($level == LevelAccount::BENDAHARA){
            return route("dashboard");
        }else{
            return  FALSE;
        }
    }
}
