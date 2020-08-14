<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class FileBase implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        if (count(explode(".",$value)) === 1){
            return NULL;
        }
        $uri = url("storage/".str_replace("public/","",$value));
        return $uri;
    }


    public function set($model, $key, $value, $attributes)
    {
        return $value;
    }
}
