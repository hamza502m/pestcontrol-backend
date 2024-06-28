<?php
// app/Repositories/UserRepository.php

namespace App\Repositories;

use App\Models\Employee;
use App\Models\User;
use App\Models\EmployeeInsurance;
use App\Models\EmployeeOtherInfo;
use App\Notifications\AppNotification;
use App\Events\ActivityPerformed;

class EmployeeRepository
{
    protected $model;

    public function all($request)
    {
         $employee=User::with('employee_details','attachments','employee_details.employee_insurance','employee_details.employee_other_info')->orderBy('id', 'DESC')->paginate(isset($request->limit)?$request->limit:50);
            if($employee){
                return response()->json(['data' => $employee]);
            }
            else{
                return response()->json(['data' => 'No data']);
            }
    }

    public function find($id)
    {
         $employee=User::where('id',$id)->with('employee_details','attachments','employee_details.employee_insurance','employee_details.employee_other_info','stock_assigned')->first();
            if($employee){return response()->json(['data' => $employee]);}
            else{return response()->json(['data' => 'No data']);}
    }

    public function create($request)
    {
            $user=registering_user($request);
            if(isset($user['error'])){
             return response()->json(['error' => $user['error']], 422);
            }
            $employee = new Employee();
            $employee->user_id=$user['data']->id;
            $employee->eid_no=$request->eid_no;
            $employee->eid_start=$request->eid_start;
            $employee->eid_start=$request->eid_expiry;
            $employee->profession=$request->profession;
            $employee->passport_no=$request->passport_no;
            $employee->passport_start=$request->passport_start;
            $employee->passport_expiry=$request->passport_expiry;
            $employee->save();
            if($employee){
            $this->employee_insurance($employee->id,$request);
            $this->employee_other_info($employee->id,$request);
            }

            $message="A employee has been added into system by ".$user['data']->name;
            auth()->user()->notify(new AppNotification($message));
            return response()->json([
                'message' => 'Employee Added',
                'data' => $user['data']
            ]);
    }
    public function employee_insurance($emp_id,$request)
    {
            $employee = new EmployeeInsurance();
            $employee->emp_id=$emp_id;
            $employee->hi_status=$request->hi_status;
            $employee->hi_start=$request->hi_start;
            $employee->hi_expiry=$request->hi_expiry;
            $employee->ui_status=$request->ui_status;
            $employee->ui_start=$request->ui_start;
            $employee->ui_expiry=$request->ui_expiry;
            $employee->dm_card=$request->dm_card;
            $employee->dm_start=$request->dm_start;
            $employee->dm_expiry=$request->dm_expiry;
            $employee->save();
    }
    public function employee_other_info($emp_id,$request)
    {
            $employee = new EmployeeOtherInfo();
            $employee->emp_id=$emp_id;
            $employee->relation=$request->relation;
            $employee->emergency_contact=$request->emergency_contact;
            $employee->basic_salary=$request->basic_salary;
            $employee->allowance=$request->allowance;
            $employee->other=$request->total_salary;
            $employee->save();
    }
    public function assigning_stock($request)
    {
        return assigning_stock_to_employee($request);
    }
}
