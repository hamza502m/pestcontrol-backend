<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Countries extends Model
{
    use HasFactory;

    public function states(){
        return $this->hasMany(State::class, 'country_id', 'id');
    }
    public function cities(){
        return $this->hasMany(Cities::class, 'country_id', 'id');
    }
}
