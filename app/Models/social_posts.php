<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class social_posts extends Model
{
    use HasFactory;
    // protected $fillable = ['type','page_id','page_name','page_link','page_img','post_id','post_img','post_link','post_caption','post_date'];
    
    protected $fillable = [
        'api_account_id',
        'post_id',
        'post_img',
        'post_video',
        'post_link',
        'post_title',
        'content',
        'post_date'
    ];

    public function api(): BelongsTo
    {
        return $this->belongsTo(Api::class, 'api_account_id');
    }
}
