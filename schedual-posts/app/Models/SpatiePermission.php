<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class SpatiePermission extends SpatieRole

{
    protected $fillable = ['role_color'];
}