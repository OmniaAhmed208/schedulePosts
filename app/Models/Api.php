<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Api extends Model
{
    use HasFactory;

    // protected $fillable = ['user_name','creator_id','email','user_pic','user_account_id','social_type','token','token_secret','user_status','page_name','update_interval'];
    protected $fillable = [
        'creator_id',
        'account_type',
        'account_id',
        'account_name',
        'email',
        'account_pic',
        'account_link',
        'token',
        'token_secret',
        'update_interval',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function social_posts(): HasMany
    {
        return $this->hasMany(social_posts::class, 'api_account_id');
    }
}
