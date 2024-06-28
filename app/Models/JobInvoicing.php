<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobInvoicing extends Model
{
    use HasFactory;

        protected $fillable = [
        'job_id',
        'client_id',
        'billing_frequency',
        'billing_method'
    ];

    public function jobs(){
        return $this->belongsTo(Job::class,'job_id','id');
    }
}
