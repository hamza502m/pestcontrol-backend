<?php

namespace App\Http\Controllers;

use App\Models\ClientJob;
use App\Models\ClientJobDetails;
use App\Models\JobSchedulePlan;
use App\Repositories\JobRepository;
use App\Models\ScheduleShiftDetails;
use App\Models\JobInvoicing;
use App\Models\AssignedJobs;
use App\Notifications\AppNotification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JobController extends Controller
{   
    /**
     * @var JobRepository
     */
    private $jobRepository;

    public function __construct(JobRepository $jobRepository)
    {
        $this->jobRepo = $jobRepository;
    }

    // Getting all jobs
    public function index(Request $request)
    {
        return $this->jobRepo->all($request);
    }
    // Getting single job
    public function singleJob($id)
    {
        return $this->jobRepo->find($id);
    }
    public function store(Request $request)
    {
        return $this->jobRepo->create($request);
    }
    // saving clientJob Details
    function clientJobDetails($job_id,$request){
            $jobdetail = new ClientJobDetails();
            $jobdetail->job_id=$job_id;
            $jobdetail->client_id=$request->client_id;
            $jobdetail->job_type=$request->job_type;
            $jobdetail->job_nature=$request->job_nature;
            $jobdetail->dates=json_encode($request->dates);
            $jobdetail->time=$request->time;
            $jobdetail->job_priority=$request->job_priority;
            $jobdetail->job_instructions=$request->job_instructions;
            $jobdetail->working_remainder=$request->working_remainder;
            $jobdetail->job_details_description=$request->job_details_description;
            $jobdetail->save();
    }

    //saving job Schedule Plan
    function jobSchedulePlan($job_id,$request){
            $jobschedule_plan = new JobSchedulePlan();
            $jobschedule_plan->job_id=$job_id;
            $jobschedule_plan->client_id=$request->client_id;
            $jobschedule_plan->schedule_frequency=$request->schedule_frequency;
            $jobschedule_plan->schedule_start_date=$request->schedule_start_date;
            $jobschedule_plan->start_time=$request->start_time;
            $jobschedule_plan->end_time=$request->end_time;
            $jobschedule_plan->shift=$request->shift;
            $jobschedule_plan->save();
            if($jobschedule_plan){
              $this->scheduleShiftDetails($jobschedule_plan->id,$request);  
            }
    }
    //saving schedule Shift Details
    function scheduleShiftDetails($jobSchedulePlan_id,$request){
            $shiftDetail = new ScheduleShiftDetails();
            $shiftDetail->ssd_id=$jobSchedulePlan_id;
            $shiftDetail->frquency_type=$request->frquency_type;
            $shiftDetail->frquency_every=$request->frquency_every;
            $shiftDetail->frquency_nature=$request->frquency_nature;
            $shiftDetail->day=json_encode($request->day);
            $shiftDetail->save();
    }
    //saving job Invoicings
    function jobInvoicings($job_id,$request){
            $shiftDetail = new JobInvoicing();
            $shiftDetail->job_id=$job_id;
            $shiftDetail->client_id=$request->client_id;
            $shiftDetail->billing_frequency=$request->billing_frequency;
            $shiftDetail->billing_method=$request->billing_method;
            $shiftDetail->save();
    }
    //assigning Job
    public function assigning_job_to_team(Request $request){
        return $this->jobRepo->assigning_job_to_team($request);
    }
    //getting assigned Job
    public function getting_assign_jobs(Request $request){
        $assigned_jobs=AssignedJobs::with('job','job.client_contract','job.quotes','captain')->get();
        $assigned_jobs->map(function ($assigned_job) {
                $assigned_job->team_members = $assigned_job->team;
                return $assigned_job;
            });
      return response()->json(['data' => $assigned_jobs]);
    }
    //Reschedual Job
    public function reschedual_job(Request $request){
        return $this->jobRepo->reschedual_job($request);
    }
    //Job status
    public function job_status(Request $request){
        return $this->jobRepo->job_status($request);
    }
}
