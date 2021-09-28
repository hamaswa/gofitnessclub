<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DailyDite extends Model
{
    protected $table = 'dailydite';
    protected $fillable = [
        'name', 'weight', 'qty','image'
    ];
}
