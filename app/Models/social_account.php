<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class social_account extends Model
{
    use HasFactory;

    protected $fillable = ['user_name','email','user_id','social_type','token','facePage_name','update_interval'];
}
