<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'firm_name',
        'password',
        'role',
        'social_id',
        'social_type',
        'email_verified_at',
        'status',
        'subscription',
        'created_at'
    ];
}
