<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dite extends Model
{
    protected $table = 'dite';

    protected $fillable = [
        "name","energy","weight"
    ];
}
