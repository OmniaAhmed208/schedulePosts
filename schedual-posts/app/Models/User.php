<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasRoles;
    
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'facebook_app_id',
        'facebook_token',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function time_think(): HasOne
    {
        return $this->hasOne(time_think::class, 'creator_id');
    }

    public function settingsApis(): HasMany
    {
        return $this->hasMany(settingsApi::class, 'creator_id');
    }

    public function apis(): HasMany
    {
        return $this->hasMany(Api::class, 'creator_id');
    }

    public function publishPosts(): HasMany
    {
        return $this->hasMany(publishPost::class, 'creator_id');
    }


    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(SpatiePermission::class, 'user_has_roles', 'user_id', 'role_id');
    }
}
