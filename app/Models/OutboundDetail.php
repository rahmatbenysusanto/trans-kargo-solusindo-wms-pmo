<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OutboundDetail extends Model
{
    protected $table = 'outbound_detail';
    protected $fillable = ['outbound_id', 'inventory_id', 'condition'];

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class, 'inventory_id', 'id');
    }
}
