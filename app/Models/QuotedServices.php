<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotedServices extends Model
{
    use HasFactory;
    protected $fillable = [
        'quotedServices_id',
        'service_id',
        'service_name',
        'no_of_months',
        'job_type',
        'rate',
        'sub_total',
        'date',
        'quotedServices_type',
    ];
    public function Quoted_Services()
    {
        return $this->morphTo();
    }
}
