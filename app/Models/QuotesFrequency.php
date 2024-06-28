<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotesFrequency extends Model
{
    use HasFactory;


    public function quote(){
        return $this->belongsTo(Quotes::class,'quote_id','id');
    }
}
