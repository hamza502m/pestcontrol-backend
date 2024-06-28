<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleShiftDetails extends Model
{
    use HasFactory;


    public function jobs(){
        return $this->belongsTo(JobSchedulePlan::class,'ssd_id','id');
    }
}
