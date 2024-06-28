<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\QuotedServices;

class ClientJob extends Model
{
    use HasFactory;

    public function job_detail()
    {
        return $this->hasOne(ClientJobDetails::class, 'job_id', 'id');
    }

    public function job_invoicing_detail()
    {
        return $this->hasOne(JobInvoicing::class, 'job_id', 'id');
    }
    public function job_schedule_plan()
    {
        return $this->hasOne(JobSchedulePlan::class, 'job_id', 'id');
    }
    public function client(){
        return $this->belongsTo(Client::class,'client_id','id');
    }
    public function client_quotes()
    {
        return $this->hasOne(Quotes::class, 'client_id', 'id');
    }
    public function client_contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id', 'id');
    }
    public function quoted_services()
    {
        return $this->morphMany(QuotedServices::class,'quotedServices');
    }
    public function quotes()
    {
        return $this->belongsTo(Quotes::class);
    }
    public function getServicesAttribute()
    {
        $services_ids = is_array($this->services_ids) ? $this->services_ids : json_decode($this->services_ids, true);
        return QuotedServices::whereIn('service_id', $services_ids)->get();
    }
    public function assignedJobs()
    {
        return $this->belongsTo(AssignedJobsUser::class);
    }
}
