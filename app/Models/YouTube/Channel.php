<?php

namespace App\Models\YouTube;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;

    protected $fillable = [
        'google_id',
        'channel_id',
        'title',
        'description',
        'thumbnail',
        'subscriber_count',
        'video_count',
        'view_count',
    ];
}
