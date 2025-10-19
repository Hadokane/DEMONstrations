<x-app-layout>

<div class="container mx-auto mt-6">
    <h1 class="text-2xl font-bold mb-2">{{ $track->title }}</h1>
    <p class="text-gray-500 mb-4">Artist: {{ $track->artist ?? 'Unknown' }}</p>

    <form method="POST" action="{{ route('tracks.play', $track) }}">
        @csrf
        <button class="bg-green-500 text-white px-4 py-2 rounded">▶️ Play</button>
    </form>

    <div class="mt-4">
        <h3 class="font-semibold">React:</h3>
        <form method="POST" action="{{ route('tracks.react', $track) }}">
            @csrf
            <button name="type" value="like" class="px-2">👍</button>
            <button name="type" value="dislike" class="px-2">👎</button>
            <button name="type" value="laugh" class="px-2">😂</button>
            <button name="type" value="wow" class="px-2">😮</button>
            <button name="type" value="sad" class="px-2">😢</button>
        </form>
    </div>

    <div class="mt-6">
        <h3 class="font-semibold">Add a Comment</h3>
        <form method="POST" action="{{ route('tracks.comment', $track) }}">
            @csrf
            <textarea name="body" rows="2" class="w-full border rounded p-2" placeholder="Your feedback..."></textarea>
            <button class="mt-2 bg-blue-500 text-white px-4 py-2 rounded">Post</button>
        </form>
    </div>

    <div class="mt-6">
        <h3 class="font-semibold mb-2">Comments:</h3>
        @foreach($track->comments as $comment)
            <div class="border-b py-2">
                <strong>{{ $comment->user->first_name ?? 'User' }}:</strong>
                <p>{{ $comment->body }}</p>
                @if($comment->timestamp_ms)
                    <p class="text-xs text-gray-400">Timestamp: {{ round($comment->timestamp_ms / 1000, 1) }}s</p>
                @endif
            </div>
        @endforeach
    </div>
</div>

</x-app-layout>