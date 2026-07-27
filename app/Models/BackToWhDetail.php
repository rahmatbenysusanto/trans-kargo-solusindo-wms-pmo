<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BackToWhDetail extends Model
{
    protected $table = 'back_to_wh_detail';
    protected $fillable = [
        'back_to_wh_id',
        'inventory_id',
        'product_id',
        'serial_number',
        'part_name',
        'part_number',
        'condition',
        'reason'
    ];

    public function backToWh(): BelongsTo
    {
        return $this->belongsTo(BackToWh::class, 'back_to_wh_id');
    }

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
