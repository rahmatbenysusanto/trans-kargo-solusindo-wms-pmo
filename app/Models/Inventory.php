<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventory';
    protected $fillable = [
        'product_id',
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
        'pic'
    ];
}
