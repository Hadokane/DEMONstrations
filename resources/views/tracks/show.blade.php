<x-app-layout>
    
@php
    $canManage = auth()->check() && (
        auth()->id() === $track->user_id || auth()->user()->is_admin
    );

    $canPlayTrack = request()->query('play') == 1;

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
            ? 'px-3 py-2 rounded border border-indigo-600 bg-indigo-600 text-white'
            : 'px-3 py-2 rounded border border-gray-300 bg-white hover:bg-gray-50';
@endphp

<div class="container mx-auto mt-6">
    @if($track->visibility === 'private' && $canManage)
        <div class="bg-white shadow rounded p-4 mb-4">
            <h2 class="text-lg font-semibold mb-2">Share this private track</h2>
            <p class="text-sm text-gray-600 mb-4">
                Grant access to other users. <br/>
                They will be able to see this track in their dashboard and activity views.
            </p>

            <form method="POST" action="{{ route('tracks.share', $track) }}" class="flex items-center space-x-3 mb-4">
                @csrf
                <div>
                    <x-input-label for="share_email" value="Enter user email:" />
                    <x-text-input id="share_email"
                                name="email"
                                type="email"
                                class="mt-1 block"
                                placeholder="user@example.com"
                                required />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    <x-primary-button class="mt-2">
                        Add
                    </x-primary-button>
                </div>
            </form>

            <h3 class="text-sm font-semibold mb-2">Currently shared with:</h3>
            @php
                $sharedAccesses = $track->accesses()->with('user')->get();
            @endphp

            @if($sharedAccesses->isEmpty())
                <p class="text-xs text-gray-500">Not shared with anyone yet.</p>
            @else
                <ul class="divide-y divide-gray-200 text-sm">
                    @foreach($sharedAccesses as $access)
                        <li class="py-2 flex items-center justify-between">
                            <div>
                                <p class="font-medium">{{ $access->user->artist_name }}</p>
                                <p class="text-xs text-gray-500">{{ $access->user->email }}</p>
                            </div>
                            <form method="POST"
                                action="{{ route('tracks.unshare', [$track, $access->user]) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-xs text-red-600 hover:underline"
                                        onclick="return confirm('Remove access for this user?')">
                                    Remove
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endif
    <div class="bg-white shadow rounded p-4 mb-4">
        <div class="flex">
            @if($track->cover_image_path)
                <img src="{{ asset('storage/'.$track->cover_image_path) }}"
                    class="mr-4 w-48 h-48 object-cover rounded mr-4"
                    alt="Cover image for {{ $track->title }}">
            @else
                <div class="mr-4 w-48 h-48 bg-gray-200 flex items-center justify-center rounded text-gray-500">
                    No Cover
                </div>
            @endif
            <div class="flex-1">
                <h1 class="text-2xl font-bold mb-2">{{ $track->title }}</h1>
                    
                    @if($track->visibility === 'private')
                    <span class="text-xs inline-flex items-center px-2 py-0.5 rounded bg-gray-800 text-white">Private</span>
                    @else
                    <span class="text-xs inline-flex items-center px-2 py-0.5 rounded bg-emerald-600 text-white">Public</span>
                    @endif

                    <p class="text-gray-700 mt-2 mb-2">Artist: {{ $track->owner->artist_name }}</p>
                    <p class="text-gray-700 mt-2 mb-2">Plays: {{ $plays }}</p>

                    @if($canManage)
                    <div class="flex space-x-2">
                        <a href="{{ route('tracks.edit', $track) }}"
                        class="inline-flex items-center px-3 py-2 text-sm rounded bg-indigo-600 text-white hover:bg-indigo-700">
                            Edit Track
                        </a>

                        @include('tracks.partials.delete-track-form', ['track' => $track])
                    </div>
                    @endif
            </div>
        </div>

        <div class="bg-white shadow rounded p-4 m-1">
            @if($track->audio_file_path && $canPlayTrack)
                <audio id="audio-player" controls class="w-full mb-4">
                    <source src="{{ asset('storage/'.$track->audio_file_path) }}" type="audio/mpeg">
                        Your browser does not support the audio element.
            @endif
            @if($track->audio_file_path && !$canPlayTrack)
                <form method="POST" action="{{ route('tracks.play', $track) }}" class="mt-2">
                    @csrf
                    <button
                        type="submit"
                        class="inline-flex items-center bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded"
                    >
                        ⏯️ Play Track
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="bg-white shadow rounded p-4 mb-4">
        <div>
            <h3 class="font-semibold">React:</h3>
            <form method="POST" action="{{ route('tracks.react', $track) }}">
                @csrf
                <button name="type" value="like" class="{{ $btn($userReaction === 'like') }}">👍 {{ $likes }}</button>
                <button name="type" value="dislike" class="{{ $btn($userReaction === 'dislike') }}">👎 {{ $dislikes }}</button>
                <button name="type" value="laugh" class="{{ $btn($userReaction === 'laugh') }}">😂 {{ $laughs }}</button>
                <button name="type" value="wow" class="{{ $btn($userReaction === 'wow') }}">😮 {{ $wows }}</button>
                <button name="type" value="sad" class="{{ $btn($userReaction === 'sad') }}">😢 {{ $sads }}</button>
            </form>
            
            @if($userReaction)
                <form method="POST"
                    action="{{ route('tracks.reaction.clear', $track) }}"
                    class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="text-xs text-gray-500 hover:text-red-600 hover:underline mt-1 mb-2">
                        Clear reaction
                    </button>
                </form>
            @endif

            @if(!is_null($approval))
            <p class="text-sm text-gray-500">Approval Rating: {{ $approval }}%</p>
            @endif
        </div>
        <div class="bg-gray-100 h-0.5 mt-2 mb-2"></div>
        <div class="mb-4">
            <h3 class="font-semibold">Add a Comment</h3>
            <form method="POST" action="{{ route('tracks.comment', $track) }}">
                @csrf
                <textarea name="body" rows="2" class="w-full border rounded p-2" placeholder="Your comment here..."></textarea>
                <button class="mt-2 bg-indigo-600 px-4 py-2 rounded text-white">Post Comment</button>
            </form>
        </div>

    </div>

    <div class="bg-white shadow rounded p-4 mb-4">
        <h3 class="font-semibold mb-2">Comments ({{ $commentsCount }}):</h3>
        @foreach($orderedComments as $comment)
            <div class="border-b py-2">
                <strong>{{ $comment->user->first_name ?? 'Guest User' }}:</strong>
                <p>{{ $comment->body }}</p>

                @if($comment->timestamp_ms)
                    <p class="text-xs">Track Timestamp: {{ round($comment->timestamp_ms / 1000, 1) }}</p>
                @endif

                <p class="text-xs">Left At: {{ $comment->created_at }}</p>
                
                @if(auth()->id() === $comment->user_id || auth()->user()->is_admin)
                    <form method="POST"
                        action="{{ route('comments.destroy', $comment) }}"
                        class="inline"
                        onsubmit="return confirm('Delete this comment?');">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            class="text-red-600 hover:underline text-sm">
                            Delete
                        </button>
                    </form>
                @endif
                
            </div>
        @endforeach
    </div>
</div>

</x-app-layout>