<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPurchase extends Model
{
    use HasFactory;

    public function items()
    {
        return $this->hasMany(StockIn::class, 'p_order_id', 'id');
    }
    
    // Define an accessor for total payments
    public function getTotalPaymentsAttribute()
    {
        return $this->items->sum('total_price');
    }

    // Ensure the accessor is appended to the model's array form
    protected $appends = ['total_payments'];
}
