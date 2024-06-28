<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeInsurance extends Model
{
    use HasFactory;
    protected $fillable = [
        'emp_id',
        'hi_status',
        'hi_start',
        'hi_expiry',
        'ui_status',
        'ui_start',
        'ui_expiry',
        'dm_card',
        'dm_start',
        'dm_expiry'
    ];
    public function employee(){
        return $this->belongsTo(Employee::class,'emp_id','id');
    }
}
