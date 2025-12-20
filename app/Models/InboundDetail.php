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
        'eos_date'
    ];

    public function inbound(): BelongsTo
    {
        return $this->belongsTo(Inbound::class, 'inbound_id');
    }
}
