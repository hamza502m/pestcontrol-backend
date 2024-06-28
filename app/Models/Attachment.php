<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
     use HasFactory;
    protected $fillable = [
        'attachment',
        'status',
        'attachment_id',
        'attachment_type',
        'attachment_description'
    ];
    
    protected $table = 'attachments';

    public function attachment()
    {
        return $this->morphTo();
    }
}
