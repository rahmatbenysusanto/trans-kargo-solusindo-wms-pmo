<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StorageRak extends Model
{
    protected $table = 'storage_rak';
    protected $fillable = ['storage_area_id', 'name'];

    public function storageArea(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(StorageArea::class, 'storage_area_id');
    }
}
