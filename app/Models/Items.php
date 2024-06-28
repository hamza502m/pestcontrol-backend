<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    use HasFactory;

    protected $casts = [
        'service_ids' => 'array', // Ensure service_ids is cast to an array
    ];
    public function brand()
    {
        return $this->hasOne(Brands::class, 'id', 'brand_id');
    }
    public function unit()
    {
        return $this->hasOne(Units::class, 'id', 'unit_id');
    }
    public function supplier()
    {
        return $this->hasOne(Suppliers::class, 'id', 'supplier_id');
    }
    public function item_type()
    {
        return $this->hasOne(ItemTypes::class, 'id', 'item_type');
    }
    public function getServiceAgreementsAttribute()
    {
        $serviceIds = is_array($this->service_ids) ? $this->service_ids : json_decode($this->service_ids, true);
        return ServiceAgreement::whereIn('id', $serviceIds)->get();
    }
    public function attachments(){
        return $this->morphMany(Attachment::class, 'attachment');
    }
    public function services(){
        return $this->belongsTo(Services::class);
    }
}
