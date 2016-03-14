<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Album extends Model
{
    protected $fillable = ['key', 'path', 'public', 'name'];

    public function order() {
        $this->hasMany('App\Order');
    }

    public static function generateKey() {
        return Uuid::uuid4()->toString();
    }
}
