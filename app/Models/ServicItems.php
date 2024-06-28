<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicItems extends Model
{
    use HasFactory;
    
    public function items()
    {
        return $this->hasMany(Items::class, 'id', 'item_id');
    }
}
