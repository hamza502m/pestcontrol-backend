<?php
// app/Repositories/UserRepository.php

namespace App\Repositories;

use App\Notifications\AppNotification;
use App\Models\ClientJob;
use App\Models\AssignedJobs;

class JobRepository
{
    protected $model;

    public function __construct(ClientJob $clientJob)
    {
        $this->model = $clientJob;
    }

    public function all($request)
    {
        $job=ClientJob::select('*');
        if(!empty($request->customer_id) || !empty($request->priority)){
            $job=$job->where('customer_id', 'like', '%'.$request->customer_id.'%')->where('priority', 'like', '%'.$request->priority.'%');
        }
        $jobs=$job->with('client_contract','job_detail','job_invoicing_detail','job_schedule_plan')->orderBy('id', 'DESC')->paginate(isset($request->limit)?$request->limit:50);
         if($jobs){
             return response()->json([
                'data' => $jobs
                ]);
             }
             else{
             return response()->json([
                'data' => 'No data'
                ]);
             }
    }

    public function find($id)
    {
        $jobs=ClientJob::where('id',$id)->with('job_detail','job_invoicing_detail','job_schedule_plan')->orderBy('id', 'DESC')->first();
        $jobs->services = $jobs->services;
            if($jobs){
                return response()->json(['data' => $jobs]);
             }
             else{
                return response()->json(['data' => 'No data']);
             }
    }

    public function create($request)
    {
            // try {
            $clientJob = new ClientJob();
            $clientJob->customer_id=$request->customer_id;
            $clientJob->contact_person_name = $request->full_name;
            $clientJob->contact_person_number = $request->phone_number;
            $clientJob->reffered_by=$request->reffered_by;
            $clientJob->date=$request->date;
            $clientJob->time=$request->time;
            $clientJob->priority=$request->priority;
            $clientJob->subject=$request->subject;
            $clientJob->description=$request->description;
            $clientJob->contract_id=$request->contract_id;
            $clientJob->duration=$request->duration;
            $clientJob->save();
            if($clientJob){
            save_tags($request->tags,$clientJob->id,ClientJob::class);
            if($request->job_type=='one time'){
            quoted_services($clientJob->id,$request,ClientJob::class);
            }
            // $this->clientJobDetails($clientJob->id,$request);
            // $this->jobSchedulePlan($clientJob->id,$request);
            // $this->scheduleShiftDetails($clientJob->id,$request);
            // $this->jobInvoicings($clientJob->id,$request);
            }
            $message="A job has been added for the client name ".get_user_details($request->customer_id)->name;
            auth()->user()->notify(new AppNotification($message));
            activity()->performedOn(ClientJob::find($clientJob->id))->log('Job has been added for customer '.$request->customer_name);
            return response()->json([
                'message' => 'Job Created',
                'data' => $clientJob
            ]);
        //     }catch (\Illuminate\Validation\ValidationException $e) {
        //     // Validation error occurred
        //     return response()->json([
        //         'error' => $e->validator->errors()->first(),
        //     ], 422);
        // } catch (\Exception $e) {
        //     // Other unexpected errors
        //     return response()->json([
        //         'error' => 'Something went wrong.',
        //     ], 500);
        // }
    }

    public function update($id, array $data)
    {
        $user = $this->model->findOrFail($id);
        $user->update($data);
        return $user;
    }
    public function job_creation_with_quote($id,$requests)
    {
        $i = 0;
        foreach ($requests->quoted_services as $serviceRequest) {
            $no_of_months = $serviceRequest['no_of_months'];
            $initialDate = $serviceRequest['date'];
            $date = \DateTime::createFromFormat('m-d-Y', $initialDate);
            $jobType = $serviceRequest['job_type']; // Assuming job_type is 'monthly' or 'weekly'

            if ($jobType === 'monthly' OR $jobType === 'yearly') {
                // Process monthly entries
                for ($month = 0; $month < $no_of_months; $month++) {
                    // Clone the date to avoid modifying the original date object
                    $currentDate = clone $date;
                    $currentDate->modify("+{$month} months");

                    // Check if the job already exists for the current date
                    $existingClientJob = ClientJob::where('customer_id', $requests->client->id)
                        ->where('quote_id', $id)
                        ->where('date', $currentDate->format('m-d-Y'))
                        ->first();

                    if ($existingClientJob) {
                        $servicesIds = json_decode($existingClientJob->services_ids, true);
                        if (!is_array($servicesIds)) {
                            $servicesIds = [];
                        }
                        if (!in_array($serviceRequest['service_id'], $servicesIds)) {
                            $servicesIds[] = $serviceRequest['service_id'];
                        }
                        $existingClientJob->services_ids = json_encode($servicesIds);
                        $existingClientJob->save();
                    } else {
                        $clientJob = new ClientJob();
                        $clientJob->customer_id = $requests->client->id;
                        $clientJob->contract_id = $requests->contract->id;
                        $clientJob->services_ids = json_encode([$serviceRequest['service_id']]);
                        $clientJob->quote_id = $id;
                        $clientJob->job_title = $requests->quote_title;
                        $clientJob->contact_person_name = $requests->client->full_name;
                        $clientJob->contact_person_number = $requests->client->phone_number;
                        $clientJob->date = $currentDate->format('m-d-Y');
                        $clientJob->description = $requests->description;
                        $clientJob->duration = $requests->duration;
                        $clientJob->save();
                    }
                }
            }
             elseif ($jobType === 'weekly') {
                // Process weekly entries
                $totalWeeks = $no_of_months * 4;
                for ($week = 0; $week < $totalWeeks; $week++) {
                    // Clone the date to avoid modifying the original date object
                    $currentDate = clone $date;
                    $currentDate->modify("+{$week} weeks");

                    // Check if the job already exists for the current date
                    $existingClientJob = ClientJob::where('customer_id', $requests->client->id)
                        ->where('quote_id', $id)
                        ->where('date', $currentDate->format('m-d-Y'))
                        ->first();

                    if ($existingClientJob) {
                        $servicesIds = json_decode($existingClientJob->services_ids, true);
                        if (!is_array($servicesIds)) {
                            $servicesIds = [];
                        }
                        if (!in_array($serviceRequest['service_id'], $servicesIds)) {
                            $servicesIds[] = $serviceRequest['service_id'];
                        }
                        $existingClientJob->services_ids = json_encode($servicesIds);
                        $existingClientJob->save();
                    } else {
                        $clientJob = new ClientJob();
                        $clientJob->customer_id = $requests->client->id;
                        $clientJob->contract_id = $requests->contract->id;
                        $clientJob->services_ids = json_encode([$serviceRequest['service_id']]);
                        $clientJob->quote_id = $id;
                        $clientJob->job_title = $requests->quote_title;
                        $clientJob->contact_person_name = $requests->client->full_name;
                        $clientJob->contact_person_number = $requests->client->phone_number;
                        $clientJob->date = $currentDate->format('m-d-Y');
                        $clientJob->description = $requests->description;
                        $clientJob->duration = $requests->duration;
                        $clientJob->save();
                    }
                }
            }
            elseif ($jobType === 'daily') {
            // Process daily entries
            $totalDays = $no_of_months * 30; // Assuming 30 days per month
            for ($day = 0; $day < $totalDays; $day++) {
                // Clone the date to avoid modifying the original date object
                $currentDate = clone $date;
                $currentDate->modify("+{$day} days");

                // Check if the job already exists for the current date
                $existingClientJob = ClientJob::where('customer_id', $requests->client->id)
                    ->where('quote_id', $id)
                    ->where('date', $currentDate->format('m-d-Y'))
                    ->first();

                if ($existingClientJob) {
                    $servicesIds = json_decode($existingClientJob->services_ids, true);
                    if (!is_array($servicesIds)) {
                        $servicesIds = [];
                    }
                    if (!in_array($serviceRequest['service_id'], $servicesIds)) {
                        $servicesIds[] = $serviceRequest['service_id'];
                    }
                    $existingClientJob->services_ids = json_encode($servicesIds);
                    $existingClientJob->save();
                } else {
                    $clientJob = new ClientJob();
                    $clientJob->customer_id = $requests->client->id;
                    $clientJob->contract_id = $requests->contract->id;
                    $clientJob->services_ids = json_encode([$serviceRequest['service_id']]);
                    $clientJob->quote_id = $id;
                    $clientJob->job_title = $requests->quote_title;
                    $clientJob->contact_person_name = $requests->client->full_name;
                    $clientJob->contact_person_number = $requests->client->phone_number;
                    $clientJob->date = $currentDate->format('m-d-Y');
                    $clientJob->description = $requests->description;
                    $clientJob->duration = $requests->duration;
                    $clientJob->save();
            }
        }
    }
     elseif ($jobType === 'custom') {
        // Process custom entries with specific gap
        $gap = $serviceRequest['month_gap']; // Use the gap from the service request
        if ($gap > 0) {
            $totalEntries = ceil($no_of_months / $gap); // Calculate total entries based on the gap
            for ($entry = 0; $entry < $totalEntries; $entry++) {
                $currentDate = clone $date;
                $currentDate->modify("+".($entry * $gap)." months");

                $existingClientJob = ClientJob::where('customer_id', $requests->client->id)
                    ->where('quote_id', $id)
                    ->where('date', $currentDate->format('m-d-Y'))
                    ->first();

                if ($existingClientJob) {
                    $servicesIds = json_decode($existingClientJob->services_ids, true);
                    if (!is_array($servicesIds)) {
                        $servicesIds = [];
                    }
                    if (!in_array($serviceRequest['service_id'], $servicesIds)) {
                        $servicesIds[] = $serviceRequest['service_id'];
                    }
                    $existingClientJob->services_ids = json_encode($servicesIds);
                    $existingClientJob->save();
                } else {
                    $clientJob = new ClientJob();
                    $clientJob->customer_id = $requests->client->id;
                    $clientJob->contract_id = $requests->contract->id;
                    $clientJob->services_ids = json_encode([$serviceRequest['service_id']]);
                    $clientJob->quote_id = $id;
                    $clientJob->job_title = $requests->quote_title;
                    $clientJob->contact_person_name = $requests->client->full_name;
                    $clientJob->contact_person_number = $requests->client->phone_number;
                    $clientJob->date = $currentDate->format('m-d-Y');
                    $clientJob->description = $requests->description;
                    $clientJob->duration = $requests->duration;
                    $clientJob->save();
                }
            }
        } else {
            // Handle the case where gap is zero or invalid
            // You can throw an error or log a warning here
            error_log("Invalid gap value: {$gap}");
        }
    }
            $i++;
        }
        return $existingClientJob = ClientJob::where('customer_id', $requests->client->id)->where('quote_id', $id)->get();

    }
    public function delete($id)
    {
        $user = $this->model->findOrFail($id);
        return $user->delete();
    }
    //saving job Invoicings
    public function assigning_job_to_team($request){
            $assigning_job = new AssignedJobs();
            $assigning_job->job_id=$request->job_id;
            $assigning_job->captain_id=$request->captain_id;
            $assigning_job->team=json_encode($request->team);
            $assigning_job->job_instruction=$request->job_instruction;
            $assigning_job->assigned_by=$request->assigned_by;
            $assigning_job->save();
            return response()->json([
                'message' => 'Job assigned to the team successfully',
            ]);
    }
    //Reschedual Job
    public function reschedual_job($request){
            $assigning_job = ClientJob::findOrFail($request->job_id);
            $assigning_job->date=$request->date;
            $assigning_job->time=$request->time;
            $assigning_job->save();
            $reschedual_job = AssignedJobs::findOrFail($request->job_assigned_id);
            $reschedual_job->job_id=$request->job_id;
            $reschedual_job->captain_id=$request->captain_id;
            $reschedual_job->team=json_encode($request->team);
            $reschedual_job->assigned_by=$request->assigned_by;
            $reschedual_job->reason=$request->reason;
            $reschedual_job->save();
            return response()->json([
                'message' => 'Job Reschedual successfully',
            ]);
    }
    //Job Status
    public function job_status($request){
            $assigning_job = ClientJob::find($request->job_id);
            $assigning_job->status=$request->status;
            $assigning_job->save();
            return response()->json([
                'message' => 'Job status updated',
            ]);
    }
}
