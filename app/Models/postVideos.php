<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class postVideos extends Model
{
    use HasFactory;
    protected $fillable = ['post_id','creator_id','video'];

    
    public function publishPost(): BelongsTo
    {
        return $this->belongsTo(publishPost::class, 'post_id');
    }

    public function getCreatorIdAttribute()
    {
        return $this->publishPost->creator_id;
    }
}
