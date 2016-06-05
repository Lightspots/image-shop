<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Preferences extends Model
{
    protected $fillable = ['key', 'value', 'type'];

    protected $primaryKey = 'key';
    
    public $incrementing = false;

}
