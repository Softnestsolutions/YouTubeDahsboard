<?php

namespace App\Http\Controllers;

use App\Services\YouTubeService;
use Illuminate\Http\Request;

class YouTubeController extends Controller
{
    protected $youtubeService;

    public function __construct(YouTubeService $youtubeService)
    {
        $this->youtubeService = $youtubeService;
    }

    public function showChannelInfo()
    {
        $channelInfo = $this->youtubeService->getChannelInfo();

        if (isset($channelInfo['error'])) {
            return redirect('/auth/google')->with('error', 'Authentication required.');
        }

        return view('youtube.channel', ['channelInfo' => $channelInfo]);
    }
}
