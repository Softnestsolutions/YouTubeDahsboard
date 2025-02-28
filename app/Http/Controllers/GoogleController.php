<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Google\Client;
use Google\Service\YouTube;
use Google\Service\Oauth2;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\YouTube\Channel;
use App\Models\YouTube\Auth_token;


class GoogleController extends Controller
{
    //

    public function index()
    {
        // Retrieve all YouTube channel records
        $channels = Channel::all();

        // Aggregate counts and sums
        $totalChannels = Channel::count();
        $totalSubscribers = Channel::sum('subscriber_count');
        $totalViews = Channel::sum('view_count');
        $totalVideos = Channel::sum('video_count');

        // Pass data to the view
        return view('dashboard.home', compact('channels', 'totalChannels', 'totalSubscribers', 'totalViews', 'totalVideos'));
    }

    // public function redirectTo()
    // {
    //     $client = new Client();
    //     $client->setClientId(env('GOOGLE_CLIENT_ID'));
    //     $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
    //     $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
    //     $client->setScopes([
    //         'https://www.googleapis.com/auth/youtube.readonly',
    //         'https://www.googleapis.com/auth/youtube.force-ssl',
    //         'https://www.googleapis.com/auth/userinfo.profile',
    //         'https://www.googleapis.com/auth/userinfo.email'
    //     ]);
    //     // Ensures refresh token is granted
    //     $client->setAccessType('offline');
    //     $client->setPrompt('select_account'); // Ensures refresh token is returned

    //     return redirect($client->createAuthUrl());
    // }

    // public function LoginWithGoogle()
    // {
    //     $client = new Client();
    //     $client->setClientId(env('GOOGLE_CLIENT_ID'));
    //     $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
    //     $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
    //     $client->addScope("https://www.googleapis.com/auth/youtube.readonly");
    //     $client->addScope("https://www.googleapis.com/auth/userinfo.email");
    //     $client->addScope("https://www.googleapis.com/auth/userinfo.profile");

    //     // Fetch the access token
    //     $token = $client->fetchAccessTokenWithAuthCode(request('code'));

    //     if (isset($token['error'])) {
    //         return response()->json(['error' => 'OAuth Error', 'message' => $token['error']], 400);
    //     }

    //     $client->setAccessToken($token);
    //     $refreshToken = $token['refresh_token'] ?? null;

    //     // Refresh token if expired
    //     if ($client->isAccessTokenExpired()) {
    //         $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
    //         $token = $client->getAccessToken();
    //     }

    //     // Fetch user info from Google
    //     $oauth2 = new Oauth2($client);
    //     try {
    //         $googleUser = $oauth2->userinfo->get();
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'error' => 'Failed to fetch user info',
    //             'message' => $e->getMessage(),
    //         ], 401);
    //     }

    //     // Check if user exists
    //     $user = User::where('google_id', $googleUser->id)
    //         ->orWhere('email', $googleUser->email)
    //         ->first();

    //     if (!$user) {
    //         // Create a new user
    //         $user = User::create([
    //             'name' => $googleUser->name,
    //             'email' => $googleUser->email,
    //             'google_id' => $googleUser->id,
    //             'password' => Hash::make('Password@123'), // Dummy password
    //             'google_refresh_token' => $refreshToken, // Save refresh token
    //         ]);
    //     } else {
    //         // Update user details if necessary
    //         $user->update([
    //             'name' => $googleUser->name,
    //             'google_id' => $googleUser->id,
    //             'google_refresh_token' => $refreshToken ?: $user->google_refresh_token, // Update refresh token if new
    //         ]);
    //     }

    //     // Authenticate the user
    //     Auth::login($user);

    //     // Fetch YouTube Data
    //     $youtube = new YouTube($client);
    //     try {
    //         $channelResponse = $youtube->channels->listChannels('snippet,statistics', ['mine' => true]);
    //         $channel = $channelResponse->getItems()[0] ?? null;
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'error' => 'Failed to fetch YouTube data',
    //             'message' => $e->getMessage(),
    //         ], 401);
    //     }

    //     if ($channel) {
    //         // Check if channel exists
    //         $channelTable = Channel::where('google_id', $googleUser->id)->first();

    //         if (!$channelTable) {
    //             // Insert new channel
    //             Channel::create([
    //                 'google_id' => $googleUser->id,
    //                 'channel_id' => $channel->getId(),
    //                 'title' => $channel->getSnippet()->getTitle(),
    //                 'description' => $channel->getSnippet()->getDescription(),
    //                 'thumbnail' => $channel->getSnippet()->getThumbnails()->getDefault()->getUrl(),
    //                 'subscriber_count' => $channel->getStatistics()->getSubscriberCount(),
    //                 'video_count' => $channel->getStatistics()->getVideoCount(),
    //                 'view_count' => $channel->getStatistics()->getViewCount(),
    //             ]);
    //         } else {
    //             // Update existing channel
    //             $channelTable->update([
    //                 'title' => $channel->getSnippet()->getTitle(),
    //                 'description' => $channel->getSnippet()->getDescription(),
    //                 'thumbnail' => $channel->getSnippet()->getThumbnails()->getDefault()->getUrl(),
    //                 'subscriber_count' => $channel->getStatistics()->getSubscriberCount(),
    //                 'video_count' => $channel->getStatistics()->getVideoCount(),
    //                 'view_count' => $channel->getStatistics()->getViewCount(),
    //             ]);
    //         }
    //     }

    //     // Fetch last five video analytics
    //     $data = $this->getChannelWithVideos($client);

    //     return redirect()->route('youtube.dashboard');
    // }


    // private function getChannelWithVideos($client)
    // {
    //     $youtube = new YouTube($client);

    //     try {
    //         // 1️⃣ Fetch Channel Details
    //         $channelResponse = $youtube->channels->listChannels('snippet,statistics,contentDetails', [
    //             'id' => env('YOUTUBE_CHANNEL_ID'),
    //         ]);

    //         if (empty($channelResponse->getItems())) {
    //             return ['error' => 'Channel not found'];
    //         }

    //         $channel = $channelResponse->getItems()[0];
    //         $playlistsResponse = $youtube->playlists->listPlaylists('id', [
    //             'channelId' => env('YOUTUBE_CHANNEL_ID'),
    //             'maxResults' => 50,  // Fetch up to 50 playlists (API limit)
    //         ]);

    //         $playlistCount = count($playlistsResponse->getItems());

    //         $channelData = [
    //             'channel_id' => $channel->getId(), // YouTube Channel ID
    //             'title' => $channel->getSnippet()->getTitle(),
    //             'description' => $channel->getSnippet()->getDescription(),
    //             'thumbnail' => $channel->getSnippet()->getThumbnails()->getHigh()->getUrl(),
    //             'subscriber_count' => $channel->getStatistics()->getSubscriberCount(),
    //             'video_count' => $channel->getStatistics()->getVideoCount(),
    //             'view_count' => $channel->getStatistics()->getViewCount(),
    //         ];

    //         // Update if exists, otherwise create
    //         Channel::updateOrCreate(
    //             ['channel_id' => $channel->getId()], // Search condition
    //             $channelData // Data to update or insert
    //         );


    //         // 2️⃣ Fetch the last 5 uploaded videos
    //         $videosResponse = $youtube->search->listSearch('id,snippet', [
    //             'channelId' => env('YOUTUBE_CHANNEL_ID'),
    //             'order' => 'date',
    //             'maxResults' => 5,
    //             'type' => 'video',
    //         ]);

    //         $videoIds = [];
    //         foreach ($videosResponse->getItems() as $video) {
    //             $videoIds[] = $video->getId()->getVideoId();
    //         }

    //         // 3️⃣ Fetch Video Analytics
    //         $videoAnalytics = [];
    //         if (!empty($videoIds)) {
    //             $statsResponse = $youtube->videos->listVideos('id,statistics,snippet', [
    //                 'id' => implode(',', $videoIds),
    //             ]);

    //             foreach ($statsResponse->getItems() as $video) {
    //                 $videoAnalytics[] = [
    //                     'video_id' => $video->getId(),
    //                     'title' => $video->getSnippet()->getTitle(),
    //                     'views' => $video->getStatistics()->getViewCount(),
    //                     'likes' => $video->getStatistics()->getLikeCount(),
    //                     'comments' => $video->getStatistics()->getCommentCount(),
    //                     'thumbnail' => $video->getSnippet()->getThumbnails()->getHigh()->getUrl(),
    //                 ];
    //             }
    //         }

    //         // ✅ Store in session
    //         session([
    //             'youtube_channel' => $channelData,
    //             'youtube_videos' => $videoAnalytics
    //         ]);

    //         return [
    //             'channel' => $channelData,
    //             'videos' => $videoAnalytics
    //         ];
    //     } catch (\Exception $e) {
    //         return ['error' => 'Failed to fetch data', 'message' => $e->getMessage()];
    //     }
    // }



    // API Routes
    public function redirectTo()
    {
        $client = new Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
        $client->setScopes([
            'https://www.googleapis.com/auth/youtube.readonly',
            'https://www.googleapis.com/auth/youtube.force-ssl',
            'https://www.googleapis.com/auth/userinfo.profile',
            'https://www.googleapis.com/auth/userinfo.email'
        ]);
        $client->setAccessType('offline');
        $client->setPrompt('select_account');

        return response()->json([
            'status' => 'success',
            'auth_url' => $client->createAuthUrl()
        ], 200);
    }

    public function loginWithGoogle()
    {
        $client = new Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
        $client->addScope("https://www.googleapis.com/auth/youtube.readonly");
        $client->addScope("https://www.googleapis.com/auth/userinfo.email");
        $client->addScope("https://www.googleapis.com/auth/userinfo.profile");

        // Fetch the access token
        $token = $client->fetchAccessTokenWithAuthCode(request('code'));

        if (isset($token['error'])) {
            return response()->json([
                'status' => 'error',
                'message' => $token['error']
            ], 400);
        }

        $client->setAccessToken($token);
        $refreshToken = $token['refresh_token'] ?? null;

        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            $token = $client->getAccessToken();
        }

        // Fetch user info
        $oauth2 = new Oauth2($client);
        try {
            $googleUser = $oauth2->userinfo->get();
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch user info',
                'error' => $e->getMessage()
            ], 401);
        }

        // Handle user
        $user = User::where('google_id', $googleUser->id)
            ->orWhere('email', $googleUser->email)
            ->first();

        if (!$user) {
            $user = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'password' => Hash::make('Password@123'),
            ]);
        } else {
            $user->update([
                'name' => $googleUser->name,
                'google_id' => $googleUser->id,
            ]);
        }

        // Store or update auth token
        Auth_token::updateOrCreate(
            ['user_id' => $user->id],
            [
                'google_id' => $googleUser->id,
                'refresh_token' => $refreshToken ?: Auth_token::where('user_id', $user->id)->value('refresh_token'),
            ]
        );

        Auth::login($user);

        // Fetch YouTube Data
        $youtube = new YouTube($client);
        try {
            $channelResponse = $youtube->channels->listChannels('snippet,statistics', ['mine' => true]);
            $channel = $channelResponse->getItems()[0] ?? null;
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch YouTube data',
                'error' => $e->getMessage()
            ], 401);
        }

        $channelData = null;
        if ($channel) {
            $channelTable = Channel::where('google_id', $googleUser->id)->first();

            $channelData = [
                'channel_id' => $channel->getId(),
                'title' => $channel->getSnippet()->getTitle(),
                'description' => $channel->getSnippet()->getDescription(),
                'thumbnail' => $channel->getSnippet()->getThumbnails()->getDefault()->getUrl(),
                'subscriber_count' => $channel->getStatistics()->getSubscriberCount(),
                'video_count' => $channel->getStatistics()->getVideoCount(),
                'view_count' => $channel->getStatistics()->getViewCount(),
            ];

            if (!$channelTable) {
                Channel::create(array_merge(['google_id' => $googleUser->id], $channelData));
            } else {
                $channelTable->update($channelData);
            }
        }

        $videoData = $this->getChannelWithVideos($client);

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'google_id' => $user->google_id
            ],
            'channel' => $channelData,
            'videos' => $videoData['videos'] ?? [],
            'token' => $token['access_token'],
            'refresh_token' => $refreshToken
        ], 200);
    }

    private function getChannelWithVideos($client)
    {
        $youtube = new YouTube($client);

        try {
            $channelResponse = $youtube->channels->listChannels('snippet,statistics,contentDetails', [
                'id' => env('YOUTUBE_CHANNEL_ID'),
            ]);

            if (empty($channelResponse->getItems())) {
                return ['videos' => [], 'error' => 'Channel not found'];
            }

            $channel = $channelResponse->getItems()[0];
            $channelData = [
                'channel_id' => $channel->getId(),
                'title' => $channel->getSnippet()->getTitle(),
                'description' => $channel->getSnippet()->getDescription(),
                'thumbnail' => $channel->getSnippet()->getThumbnails()->getHigh()->getUrl(),
                'subscriber_count' => $channel->getStatistics()->getSubscriberCount(),
                'video_count' => $channel->getStatistics()->getVideoCount(),
                'view_count' => $channel->getStatistics()->getViewCount(),
            ];

            Channel::updateOrCreate(
                ['channel_id' => $channel->getId()],
                $channelData
            );

            $videosResponse = $youtube->search->listSearch('id,snippet', [
                'channelId' => env('YOUTUBE_CHANNEL_ID'),
                'order' => 'date',
                'maxResults' => 5,
                'type' => 'video',
            ]);

            $videoIds = array_map(fn($video) => $video->getId()->getVideoId(), $videosResponse->getItems());

            $videoAnalytics = [];
            if (!empty($videoIds)) {
                $statsResponse = $youtube->videos->listVideos('id,statistics,snippet', [
                    'id' => implode(',', $videoIds),
                ]);

                foreach ($statsResponse->getItems() as $video) {
                    $videoAnalytics[] = [
                        'video_id' => $video->getId(),
                        'title' => $video->getSnippet()->getTitle(),
                        'views' => $video->getStatistics()->getViewCount(),
                        'likes' => $video->getStatistics()->getLikeCount(),
                        'comments' => $video->getStatistics()->getCommentCount(),
                        'thumbnail' => $video->getSnippet()->getThumbnails()->getHigh()->getUrl(),
                    ];
                }
            }

            return [
                'channel' => $channelData,
                'videos' => $videoAnalytics
            ];
        } catch (\Exception $e) {
            return [
                'videos' => [],
                'error' => 'Failed to fetch data',
                'message' => $e->getMessage()
            ];
        }
    }
}
