<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    use HasFactory;
    protected $fillable = [
        'service_name',
        'unit_price',
        'service_code',
        'status',
        'scope_of_work',
        'terms_and_conditions'
    ]; 
    public function item_services()
    {
        return $this->hasMany(ServicItems::class, 'service_id', 'id');
    }
}
