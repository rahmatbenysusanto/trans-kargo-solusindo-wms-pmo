<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Menu extends Model
{
    protected $table = 'menu';
    protected $fillable = ['name'];

    public function userHasMenu(): BelongsTo
    {
        return $this->belongsTo(UserHasMenu::class, 'id', 'menu_id');
    }
}
