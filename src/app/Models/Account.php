<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 
        'user_id',
        'merchant_card_id',
        'account_number', 
        'account_name',
        'bank_code',
        'currency', 
        'type', 
        'bank', 
        'status'
        
    ];
}
