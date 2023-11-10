<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscriber extends Model
{
    use HasFactory;

    protected $fillable = ['email'];

    public function subscriberRequests(): HasMany
    {
        return $this->hasMany(subscriberRequest::class, 'subscriber_id');
    }
}
