<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BackToWh extends Model
{
    protected $table = 'back_to_wh';
    protected $fillable = [
        'number',
        'reason',
        'received_at',
        'received_by',
        'remarks',
        'created_by'
    ];

    public function details(): HasMany
    {
        return $this->hasMany(BackToWhDetail::class, 'back_to_wh_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
