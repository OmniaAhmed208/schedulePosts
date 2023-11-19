<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class settingsApi extends Model
{
    use HasFactory;

    protected $fillable = ['creator_id','appID', 'appSecret','appType','apiKey'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

}
