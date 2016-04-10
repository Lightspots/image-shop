<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = ['order_id', 'path', 'size', 'price', 'count'];

    public function order() {
        return $this->belongsTo('App\Order');
    }
}
