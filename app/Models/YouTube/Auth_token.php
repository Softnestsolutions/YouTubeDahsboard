<?php

namespace App\Models\YouTube;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auth_token extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'token',
        'refresh_token',
        'google_id',
    ];
}
