<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StorageLantai extends Model
{
    protected $table = 'storage_lantai';
    protected $fillable = ['storage_area_id', 'storage_rak_id', 'name'];

    public function storageArea(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(StorageArea::class, 'storage_area_id');
    }

    public function storageRak(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(StorageRak::class, 'storage_rak_id');
    }
}
