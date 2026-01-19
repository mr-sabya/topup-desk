<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //
    protected $fillable = [
        'category_id',
        'provider_id',
        'phone_number',
        'account_number',
        'amount',
        'connection_type',
        'status',
        'trx_id',
        'guest_email'
    ];
}
