<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeOtherInfo extends Model
{
    use HasFactory;
    protected $fillable = [
        'emp_id',
        'relative_name',
        'relation',
        'emergency_contact',
        'basic_salary',
        'allowance',
        'other',
        'total_salary'
    ];
    public function employee(){
        return $this->belongsTo(Employee::class,'emp_id','id');
    }
}
