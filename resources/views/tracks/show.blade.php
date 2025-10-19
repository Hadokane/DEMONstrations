<x-app-layout>
    
@php 
    $plays = $track->plays->count();
    $likes = $track->likesCount();
    $dislikes = $track->dislikesCount();
    $laughs = $track->laughsCount();
    $wows = $track->wowCount();
    $sads = $track->sadCount();
    $totalVotes = $likes + $dislikes;
    $approval   = $totalVotes > 0 ? round(($likes / $totalVotes) * 100) : null;

    $orderedComments = $track->comments()->latest()->with('user')->get();
    $commentsCount   = $orderedComments->count();

    $userReaction = optional($track->reactions->firstWhere('user_id', auth()->id()))->type;
    $btn = fn(bool $selected) => $selected
            ? 'px-3 py-2 rounded border border-indigo-600 bg-indigo-600'
            : 'px-3 py-2 rounded border border-gray-300 bg-white hover:bg-gray-50';
@endphp

@if($track->audio_file_path)
    <audio controls class="w-full mb-4">
        <source src="{{ asset('storage/'.$track->audio_file_path) }}" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>
@endif

<div class="container mx-auto mt-6">
    <h1 class="text-2xl font-bold mb-2">{{ $track->title }}</h1>
    <p class="text-gray-500 mb-4">Artist: {{ $track->artist ?? 'Unknown' }}</p>

    <form method="POST" action="{{ route('tracks.play', $track) }}">
        @csrf
        <button class="inline-flex items-center bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded">⏯️ Play - {{ $plays }} Plays</button>
    </form>

    <div class="mt-4">
        <h3 class="font-semibold">React:</h3>
        <form method="POST" action="{{ route('tracks.react', $track) }}">
            @csrf
            <button name="type" value="like" class="{{ $btn($userReaction === 'like') }}">👍 {{ $likes }}</button>
            <button name="type" value="dislike" class="{{ $btn($userReaction === 'dislike') }}">👎 {{ $dislikes }}</button>
            <button name="type" value="laugh" class="{{ $btn($userReaction === 'laugh') }}">😂 {{ $laughs }}</button>
            <button name="type" value="wow" class="{{ $btn($userReaction === 'wow') }}">😮 {{ $wows }}</button>
            <button name="type" value="sad" class="{{ $btn($userReaction === 'sad') }}">😢 {{ $sads }}</button>
        </form>

        @if(!is_null($approval))
            <p class="text-sm text-gray-500">Approval Rating: {{ $approval }}%</p>
        @endif
    </div>



    <div class="mt-6">
        <h3 class="font-semibold">Add a Comment</h3>
        <form method="POST" action="{{ route('tracks.comment', $track) }}">
            @csrf
            <textarea name="body" rows="2" class="w-full border rounded p-2" placeholder="Your comment here..."></textarea>
            <button class="mt-2 bg-blue-500 px-4 py-2 rounded">Post Comment</button>
        </form>
    </div>

    <div class="mt-6">
        <h3 class="font-semibold mb-2">Comments ({{ $commentsCount }}):</h3>
        @foreach($orderedComments as $comment)
            <div class="border-b py-2">
                <strong>{{ $comment->user->first_name ?? 'Guest User' }}:</strong>
                <p>{{ $comment->body }}</p>

                @if($comment->timestamp_ms)
                    <p class="text-xs">Track Timestamp: {{ round($comment->timestamp_ms / 1000, 1) }}</p>
                @endif

                <p class="text-xs">Left At: {{ $comment->created_at }}</p>
            </div>
        @endforeach
    </div>

</div>

</x-app-layout>