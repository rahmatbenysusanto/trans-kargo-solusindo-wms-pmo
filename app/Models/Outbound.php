<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Outbound extends Model
{
    protected $table = 'outbound';
    protected $fillable = [
        'number',
        'client_id',
        'site_location',
        'type',
        'qty',
        'delivery_date',
        'received_by',
        'courier',
        'tracking_number',
        'remarks',
        'created_by'
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
