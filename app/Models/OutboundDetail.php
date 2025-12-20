<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutboundDetail extends Model
{
    protected $table = 'outbound_detail';
    protected $fillable = ['outbound_id', 'inventory_id', 'condition'];
}
