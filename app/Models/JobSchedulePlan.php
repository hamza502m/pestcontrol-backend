<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobSchedulePlan extends Model
{
    use HasFactory;
    protected $fillable = [
        'job_id',
        'client_id',
        'schedule_start_date',
        'schedule_frequency',
        'start_time',
        'end_time',
        'shift'
    ];

    public function jobs(){
        return $this->belongsTo(Job::class,'job_id','id');
    }
    public function shift_schedule_details()
    {
        return $this->hasOne(ScheduleShiftDetails::class, 'ssd_id', 'id');
    }
}
