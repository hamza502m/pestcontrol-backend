<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'eid_no',
        'eid_start',
        'eid_expiry',
        'profession',
        'passport_no',
        'passport_start',
        'passport_expiry',
    ];
    public function employee_insurance()
    {
        return $this->hasOne(EmployeeInsurance::class, 'emp_id', 'id');
    }
    public function employee_other_info()
    {
        return $this->hasOne(EmployeeOtherInfo::class, 'emp_id', 'id');
    }
}
