<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyBuying extends Model
{
    protected $table = "dailybuyings";
    
    protected $fillable = [
        'id','shop_id','brand_id','name', 'weight', 'price','frequency', 'qty','image'
    ];

}
