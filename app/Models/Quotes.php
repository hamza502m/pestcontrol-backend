<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ServiceAgreement;
use App\Models\TreatmentMethod;

class Quotes extends Model
{
    use HasFactory;
    protected $fillable = [
        'quote_title',
        'user_id',
        'client_id',
        'firm',
        'contract_reference',
        'job_type',
        'quote_title',
        'date',
        'due_date',
        'subject',
        'service_ids',
        'tm_ids',
        'trn',
        'food_watch_account',
        'discription',
        'duration',
        'discount_type',
        'discount',
        'status',
        'follow_up_date',
        'invoice_type',
        'no_of_invoive',
        'remarks',
        'vat',
        'grand_total'
    ];
    protected $casts = [
        'service_ids' => 'array', // Ensure service_ids is cast to an array
        'tm_ids' => 'array', // Ensure service_ids is cast to an array
    ];
    public function getServiceAgreementsAttribute()
    {
        $serviceIds = is_array($this->service_ids) ? $this->service_ids : json_decode($this->service_ids, true);
        return ServiceAgreement::whereIn('id', $serviceIds)->get();
    }
    public function getTreatmentMethodAttribute()
    {
        $tm_ids = is_array($this->tm_ids) ? $this->tm_ids : json_decode($this->tm_ids, true);
        return TreatmentMethod::whereIn('id', $tm_ids)->get();
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function client()
    {
        return $this->belongsTo(Client::class,'client_id','id');
    }
    public function tags()
    {
        return $this->morphMany(Tag::class, 'tag');
    }
    public function contract()
    {
        return $this->hasOne(Contract::class, 'quote_id', 'id');
    }
    public function quoted_services()
    {
        return $this->morphMany(QuotedServices::class,'quotedServices');
    }
    public function quotes_frequencies(){
        return $this->hasMany(QuotesFrequency::class, 'quote_id', 'id');
    }
    public function jobs(){
        return $this->hasMany(ClientJob::class, 'job_id', 'id');
    }
}
