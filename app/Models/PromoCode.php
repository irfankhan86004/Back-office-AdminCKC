<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class PromoCode extends Model
{
    protected $guarded = ['id'];

    /* ------------------
        Relationships
    ------------------ */

    /* ------------------
      Getters - Setters
    ------------------ */

    public function setDateExpireAttribute($value)
    {
        if($value){
            $this->attributes['date_expire'] = Carbon::createFromFormat("d/m/Y", $value)->format("Y-m-d");
        }
    }

    public function getDateExpireAttribute($value)
    {
        if($value){
            return Carbon::createFromFormat("Y-m-d", $value)->format("d/m/Y");
        }
    }

    public function getCreatedAtAttribute($value)
    {
        if($value){
            return Carbon::createFromFormat("Y-m-d H:i:s", $value)->format("d/m/Y - H:i");
        }
    }

    /* ------------------
           Methods
    ------------------ */
    public function isValid($order)
    {
        // N'a pas expiré ?
        $now = Carbon::today();
        $promoExpire = Carbon::createFromFormat('d/m/Y', $this->date_expire);
        if($now <= $promoExpire) {

            // Maximum d'utilisations dépassé ?
            if(empty($this->max_use) || ($this->used < $this->max_use)) {

                // Valeur positive ?
                if($this->value > 0 && !empty($this->type)) {

                    return [
                        'status' => true,
                    ];

                } else {
                    $message = 'Le code promo n\'a aucun effet';
                }
            } else {
                $message = 'Le code promo à atteint son maximum d\'utilisation';
            }
        } else {
            $message = 'Le code promo à expiré';
        }

        return [
            'status' => false,
            'message' => $message,
        ];
    }

    public function onThisRoom($room_id) {
        $rooms_ids = array_filter(explode(',', $this->rooms));
        if(empty($rooms_ids) || in_array($room_id, $rooms_ids)) {
            return true;
        }
        return false;
    }

    public function onThisOption($option_id) {
        $options_id = array_filter(explode(',', $this->options));
        if(!empty($options_id) && in_array($option_id, $options_id)) {
            return true;
        }
        return false;
    }

    public function affect(float $basePrice)
    {
        $newPrice = 0;
        if($this->type == 'amount') {
            $newPrice = $basePrice - $this->value;
        } elseif($this->type == 'percentage') {
            $newPrice = $basePrice - ($basePrice * $this->value / 100);
        }

        if(0 <= $newPrice && $newPrice < $basePrice) {
            return $newPrice;
        } else {
            return $basePrice;
        }
    }

}
