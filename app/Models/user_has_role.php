<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class user_has_role extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','role_id'];
    
}
