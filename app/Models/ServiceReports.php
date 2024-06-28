<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceReports extends Model
{
    use HasFactory;
    protected $casts = [
        'service_ids' => 'array', // Ensure service_ids is cast to an array
        'tm_ids' => 'array', // Ensure service_ids is cast to an array
    ];
    public function getServiceAgreementsAttribute()
    {
        $serviceIds = is_array($this->service_ids) ? $this->service_ids : json_decode($this->service_ids, true);
        return ServiceAgreement::whereIn('id', $serviceIds)->get();
    }
    public function getTreatmentMethodAttribute()
    {
        $tm_ids = is_array($this->tm_ids) ? $this->tm_ids : json_decode($this->tm_ids, true);
        return TreatmentMethod::whereIn('id', $tm_ids)->get();
    }
    public function services_areas(){
        return $this->hasMany(ServicAreas::class, 'report_id', 'id');
    }
    public function service_used_chemicals(){
        return $this->hasMany(ServiceUsedChemicals::class, 'report_id', 'id');
    }
}
