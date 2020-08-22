<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class StatusOrder implements CastsAttributes
{
    CONST WAITING_PAYMENT = 0;
    CONST PAYMENT_CONFIRMED = 1;
    CONST PROCESSING = 2;
    CONST SHIPPING = 3;
    CONST SUCCESS = 4;
    CONST RETURNED = 5;
    CONST CANCELED = 7;
    CONST CASHBON = 6;
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
        if ($type == StatusOrder::WAITING_PAYMENT){
            return "Menunggu Pembayaran";
        }elseif ($type == StatusOrder::PAYMENT_CONFIRMED){
            return "Pembayaran Di Konfirmasi";
        }elseif ($type == StatusOrder::PROCESSING){
            return "Pesanan Sedang Di Proses";
        }elseif ($type == StatusOrder::SHIPPING){
            return "Pesanan Sedang Dikirimkan";
        }elseif ($type == StatusOrder::SUCCESS){
            return "Pesanan Sukses";
        }elseif ($type == StatusOrder::RETURNED){
            return "Pesanan Dikembalikan";
        }elseif ($type == StatusOrder::CANCELED){
            return "Pesanan Di Batalkan";
        }elseif ($type == StatusOrder::CASHBON){
            return "Pembayaran Credit Di Konfirmasi";
        }else{
            return  FALSE;
        }
    }
}
