<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWeight extends Model
{
    protected $fillable = [
        'user_id', 'weight'
    ];
}
