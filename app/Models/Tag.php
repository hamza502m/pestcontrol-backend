<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
      protected $fillable = [
        'tag',
        'tag_id',
        'tag_type',
        'status'
    ];
    public function tag()
    {
        return $this->morphTo();
    }
}
