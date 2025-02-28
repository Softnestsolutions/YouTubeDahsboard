<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YouTube Dashboard</title>
</head>

<body>
    <h1>YouTube Dashboard</h1>

    <!-- Channel Info -->
    <h2>Channel Information</h2>
    @if (isset($channelInfo['items'][0]))
        <p>Name: {{ $channelInfo['items'][0]['snippet']['title'] }}</p>
        <p>Subscribers: {{ $channelInfo['items'][0]['statistics']['subscriberCount'] }}</p>
        <p>Videos: {{ $channelInfo['items'][0]['statistics']['videoCount'] }}</p>
    @endif

    <!-- Videos -->
    <h2>Latest Videos</h2>
    <ul>
        @foreach ($videos['items'] as $video)
            <li>
                <a href="https://www.youtube.com/watch?v={{ $video['id']['videoId'] }}" target="_blank">
                    {{ $video['snippet']['title'] }}
                </a>
            </li>
        @endforeach
    </ul>

    <!-- Playlists -->
    <h2>Playlists</h2>
    <ul>
        @foreach ($playlists['items'] as $playlist)
            <li>
                <a href="https://www.youtube.com/playlist?list={{ $playlist['id'] }}" target="_blank">
                    {{ $playlist['snippet']['title'] }}
                </a>
            </li>
        @endforeach
    </ul>
</body>

</html>
