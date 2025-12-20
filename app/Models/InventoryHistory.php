<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryHistory extends Model
{
    protected $table = 'inventory_history';
    protected $fillable = ['inventory_id', 'type', 'description'];
}
