<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['album_id', 'firstname', 'lastname', 'street', 'zip', 'city', 'email', 'phone', 'price', 'finish', 'remark'];

    public function album() {
        return $this->belongsTo('App\Album');
    }

    public function photo() {
        return $this->hasMany('App\Photo');
    }
}
