<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class publishPost extends Model
{
    use HasFactory;

    // protected $fillable = ['creator_id','type','postData','pageName','image','link','status','scheduledTime','tokenApp','token_secret'];

    protected $fillable = [
        'creator_id',
        'account_type',
        'account_id',
        'account_name',
        'status',
        'thumbnail',
        'link',
        'post_title',
        'content',
        'youtube_privacy',
        'youtube_tags',
        'youtube_category',
        'scheduledTime',
        'tokenApp',
        'token_secret'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    
    public function youtube_category(): BelongsTo
    {
        return $this->belongsTo(youtube_category::class, 'youtube_category');
    }

    public function postImages(): HasMany
    {
        return $this->hasMany(postImages::class, 'post_id');
    }

    public function postVideos(): HasMany
    {
        return $this->hasMany(postVideos::class, 'post_id');
    }

    public function deletePostWithImages()
    {
        if ($this->postImages()->count() > 0) {
            $this->postImages()->delete();
        }
        // $this->delete();
    }

    public function deletePostWithVideos()
    {
        if ($this->postVideos()->count() > 0) {
            $this->postVideos()->delete();
        }
        // $this->delete();
    }
}
