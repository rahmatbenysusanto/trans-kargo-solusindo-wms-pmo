<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventory';
    protected $fillable = [
        'product_id',
        'client_id',
        'inbound_detail_id',
        'bin_id',
        'qty',
        'status',
        'part_name',
        'part_number',
        'serial_number',
        'condition',
        'manufacture_date',
        'warranty_end_date',
        'eos_date',
        'pic_id',
        'pic',
        'remark'
    ];

    public function pic(): BelongsTo
    {
        return $this->belongsTo(Pic::class, 'pic_id');
    }

    public function bin(): BelongsTo
    {
        return $this->belongsTo(StorageBin::class, 'bin_id');
    }

    public function inboundDetail(): BelongsTo
    {
        return $this->belongsTo(InboundDetail::class, 'inbound_detail_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
