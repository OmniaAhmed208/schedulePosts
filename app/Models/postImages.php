<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class postImages extends Model
{
    use HasFactory;
    protected $fillable = ['post_id','creator_id','image'];


    public function publishPost(): BelongsTo
    {
        return $this->belongsTo(publishPost::class, 'post_id');
    }

    // With this setup, when you access the creator_id of a post_image, it will automatically fetch the creator_id 
    //of the related publishPost. You no longer need to remember or manage the creator_id manually when working with post_images.
    public function getCreatorIdAttribute()
    {
        return $this->publishPost->creator_id;
    }
}
