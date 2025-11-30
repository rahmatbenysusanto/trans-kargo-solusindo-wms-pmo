<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StorageBin extends Model
{
    protected $table = 'storage_bin';
    protected $fillable = ['storage_area_id', 'storage_rak_id', 'storage_lantai_id', 'name'];

    public function storageArea(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(StorageArea::class, 'storage_area_id');
    }

    public function storageRak(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(StorageRak::class, 'storage_rak_id');
    }

    public function storageLantai(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(StorageLantai::class, 'storage_lantai_id');
    }
}
