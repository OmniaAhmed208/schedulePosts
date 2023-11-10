<?php

namespace App\Models;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SpatiePermission extends Role

{
    protected $fillable = ['role_color'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}