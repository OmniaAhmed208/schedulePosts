<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class youtube_category extends Model
{
    use HasFactory;
    protected $fillable = ['category_id','category_name'];

    public function publishPost(): HasOne
    {
        return $this->hasOne(publishPost::class, 'youtube_category');
    }
}
