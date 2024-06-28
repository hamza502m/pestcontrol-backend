<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Client extends Model
{
	use Notifiable, HasFactory;
    protected $fillable = [
        'user_id',
        'full_name',
        'firm_name',
        'email',
        'phone_number',
        'mobile_number',
        'industry_name',
        'reference',
        'address',
        'city',
        'latitude',
        'longitude'
    ];

    public function tags()
    {
        return $this->morphMany(Tag::class, 'tag');
    }
    public function jobs()
    {
        return $this->hasOne(ClientJob::class, 'id', 'client_id');
    }
    public function contracts(){
        return $this->hasMany(Contract::class, 'client_id', 'id');
    }
    public function quotes(){
        return $this->hasMany(Quotes::class, 'quote_id', 'id');
    }
    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
