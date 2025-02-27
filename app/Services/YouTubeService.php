<?php

namespace App\Services;

use Google\Client;
use Google\Service\YouTube; // ✅ Correct Google namespace

class YouTubeService
{
    protected $client;
    protected $youtube;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setClientId(env('GOOGLE_CLIENT_ID'));
        $this->client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $this->client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
        $this->client->addScope(YouTube::YOUTUBE_READONLY);

        $this->youtube = new YouTube($this->client);
    }

    public function getChannelInfo()
    {
        $this->client->setAccessToken(session('google_access_token'));

        if ($this->client->isAccessTokenExpired()) {
            return redirect('/auth/google'); // Redirect to re-authenticate
        }

        return $this->youtube->channels->listChannels('snippet,statistics', ['mine' => true]);
    }
}
