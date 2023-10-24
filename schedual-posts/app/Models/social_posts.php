<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class social_posts extends Model
{
    use HasFactory;
    protected $fillable = ['type','page_id','page_name','page_link','page_img','post_id','post_img','post_link','post_caption','post_date'];
}
