<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StorageArea extends Model
{
    protected $table = 'storage_area';
    protected $fillable = ['name', 'warehouse_id'];
}
