<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inbound extends Model
{
    protected $table = 'inbound';
    protected $fillable = [
        'number',
        'client_id',
        'site_location',
        'inbound_type',
        'owner_status',
        'quantity',
        'status',
        'remarks',
        'received_at',
        'created_by'
    ];
}
