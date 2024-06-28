<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'firm_name',
        'acc_name',
        'acc_contact',
        'acc_email',
        'percentage'
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
