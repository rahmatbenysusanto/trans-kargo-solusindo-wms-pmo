<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function inboundDetail(): HasMany
    {
        return $this->hasMany(InboundDetail::class, 'inbound_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
