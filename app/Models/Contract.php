<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ServiceAgreement;
use App\Models\TreatmentMethod;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'quote_id',
        'user_id',
        'contracted_by',
        'firm',
        'contract_reference',
        'contract_title',
        'contract_subject',
        'food_watch_account',
        'job_type',
        'date',
        'due_date',
        'service_ids',
        'trn',
        'tm_ids',
        'discount_type',
        'discount',
        'vat',
        'grand_total',
        'status',
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
    public function tags()
    {
        return $this->morphMany(Tag::class, 'tag');
    }
    public function quote()
    {
        return $this->belongsTo(Quotes::class, 'quote_id', 'id');
    }
    public function client(){
        return $this->belongsTo(Client::class,'client_id','id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function job(){
        return $this->hasOne(ClientJob::class,'job_id','id');
    }
}
