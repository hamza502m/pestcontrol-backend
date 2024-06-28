<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suppliers extends Model
{
    use HasFactory;
	
	public function country()
    {
        return $this->hasOne(Countries::class, 'id', 'country');
    }	
	public function state()
    {
        return $this->hasOne(State::class, 'id', 'state');
    }	
	public function city()
    {
        return $this->hasOne(Cities::class, 'id', 'city');
    }   
    public function items()
    {
        return $this->hasMany(Items::class, 'item_id', 'id');
    }   
    public function order_purchased()
    {
        return $this->hasMany(OrderPurchase::class, 'supplier_id', 'id');
    }
    public function getTotalPaymentsAttribute()
    {
        return $this->order_purchased->reduce(function ($carry, $order) {
            return $carry + $order->items->sum('total_price');
        }, 0);
    }
    protected $appends = ['total_payments'];
}
