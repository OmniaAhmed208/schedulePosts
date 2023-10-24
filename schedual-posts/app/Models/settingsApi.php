<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class settingsApi extends Model
{
    use HasFactory;

    protected $fillable = ['appID', 'appSecret','appType'];
}
