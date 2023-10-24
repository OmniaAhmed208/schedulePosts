<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class time_think extends Model
{
    use HasFactory;
    protected $fillable = ['creator_id','time'];
}
