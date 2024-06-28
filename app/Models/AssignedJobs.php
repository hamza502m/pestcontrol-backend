<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class AssignedJobs extends Model
{
    use HasFactory;
    protected $fillable = [
        'job_id',
        'captain_id',
        'team',
        'job_instruction',
        'assigned_by'
    ];
    protected $casts = [
        'team' => 'array',
    ];
    public function getTeamMembersAttribute()
    {
        $user_ids = is_array($this->team) ? $this->team : json_decode($this->team, true);
        return User::whereIn('id', $user_ids)->get();
    }
    public function captain()
    {
        return $this->belongsTo(User::class,'captain_id','id');
    }
    public function job(){
        return $this->hasOne(ClientJob::class, 'id', 'job_id');
    }
}
