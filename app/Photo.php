<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = ['order_id', 'path', 'size', 'price'];

    public function order() {
        return $this->hasOne('App\Order');
    }
}
