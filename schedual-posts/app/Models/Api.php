<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api extends Model
{
    use HasFactory;

    protected $fillable = ['user_name','creator_id','email','user_pic','user_account_id','social_type','token','token_secret','user_status','page_name','update_interval'];
}
