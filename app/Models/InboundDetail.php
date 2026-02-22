<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InboundDetail extends Model
{
    protected $table = 'inbound_detail';
    protected $fillable = [
        'inbound_id',
        'product_id',
        'qty',
        'qty_pa',
        'part_name',
        'part_number',
        'serial_number',
        'condition',
        'manufacture_date',
        'warranty_end_date',
        'eos_date',
        'remarks'
    ];

    public function inbound(): BelongsTo
    {
        return $this->belongsTo(Inbound::class, 'inbound_id');
    }

    public function inventory(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Inventory::class, 'inbound_detail_id');
    }
}
