<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'contract_id',
        'user_id',
        'customer_name',
        'customer_number',
        'reference',
        'subject',
        'priority',
        // Login user will a invoiced user
        'invoiced_by',
        'due_on',
        'duration',
        'due_amount',
        'discount_type',
        'discount',
        'vat',
        'grand_total',
        'status',
    ];
}
