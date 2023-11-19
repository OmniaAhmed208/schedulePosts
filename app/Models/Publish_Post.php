<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publish_Post extends Model
{
    use HasFactory;
    protected $fillable = ['creator_id','type','postData','pageName','image','link','status','scheduledTime','tokenApp','token_secret'];
}
