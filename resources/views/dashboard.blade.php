<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </x-slot>

    <div class="container mx-auto mt-6">
        <div class="mt-4 mb-4 grid grid-cols-2 gap-6">
            <h1 class="text-2xl font-bold mb-4">🎧 Your Dashboard</h1>
            <div class="flex justify-end m-2">
                <a href="{{ route('tracks.upload.form') }}">
                    <x-primary-button>
                        + Upload Track
                    </x-primary-button>
                </a>
            </div>
        </div>

        <div class="mt-4 mb-4 grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div id="tracks-list">
                <div class="bg-white shadow rounded p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold">🎵 Your Recent Tracks</h3>
                        <a href="{{ route('activity.index', ['tab' => 'tracks']) }}"
                            class="text-xs text-indigo-600 hover:underline">
                            View all
                        </a>
                    </div>

                @foreach($recentTracks as $track)
                    @php
                        $canManage = auth()->id() === $track->user_id || auth()->user()->is_admin;
                    @endphp

                <div class="bg-white shadow rounded p-4 mb-4 flex">
                    <a href="{{ route('tracks.show', $track) }}">
                        <x-cover-image :track="$track" size="lg" />
                    </a>

                    <div class="flex-1">
                        <h2 class="text-xl font-semibold hover:text-indigo-600"> 
                            <a href="{{ route('tracks.show', $track) }}">
                                💿 {{ $track->title }}
                            </a>
                        </h2>

                        <x-track-visibility-badge :track="$track" />
                        <p class="text-gray-600">Artist: {{ $track->owner->artist_name }}</p>

                        @php 
                            $stats = $track->stats; 
                        @endphp

                        <p class="mt-2">
                            ▶️ Plays: {{ $stats->plays }}
                            <br />
                            👍 {{ $stats->likes }}
                            | 👎 {{ $stats->dislikes }}
                            | 😂 {{ $stats->laughs }}
                            | 😮 {{ $stats->wows }}
                            | 😢 {{ $stats->sads }}
                            | 💬 {{ $stats->comments }}
                        </p>

                        @if(!is_null($stats->approval))
                            <p class="text-sm text-gray-500">
                                Approval Rating: {{ $stats->approval }}%
                            </p>
                        @endif

                        <p class="text-sm text-gray-500">Unique Listeners: {{ $track->plays->unique('user_id')->count() }}</p>
                        
                        @if($canManage)
                            <a href="{{ route('tracks.edit', $track) }}"
                                class="text-sm text-indigo-600 hover:underline">
                                Edit
                            </a>

                            <form action="{{ route('tracks.destroy', $track) }}"
                                    method="POST"
                                    class="inline ml-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-red-600 hover:underline text-sm"
                                        onclick="return confirm('Delete this track?')">
                                    Delete
                                </button>
                            </form>
                        @endif  

                    </div>
                </div>
                @endforeach

                @if($recentTracks->isEmpty())
                    <div class="border-t border-gray-300 mt-4">
                        <h2><strong>No tracks found.</strong></h2>
                        <img src="{{ asset('img/empty-demon.png') }}" 
                        alt="Empty Container Demon"
                        class=""
                        width="120" 
                        height="120">
                    </div>
                @endif
            </div>
        </div>
        
        <div>
            @if($trendingTracks->isNotEmpty())
                <div class="bg-white shadow rounded p-4 mb-6">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-lg font-semibold">🔥 Trending Tracks</h2>
                        <a href="{{ route('tracks.trending') }}"
                        class="text-xs text-indigo-600 hover:underline">
                            View all
                        </a>
                    </div>

                    <ul class="divide-y divide-gray-200">
                        @foreach($trendingTracks as $t)
                            <li class="py-2 flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('tracks.show', $t) }}">
                                        <x-cover-image :track="$t" size="sm" />
                                    </a>

                                    <div>
                                        <a href="{{ route('tracks.show', $t) }}"
                                        class="font-medium text-gray-900 hover:text-indigo-600">
                                            {{ $t->title }}
                                        </a>
                                        <p class="text-xs text-gray-500">
                                            {{ $t->owner->artist_name ?? 'Unknown artist' }}
                                        </p>
                                    </div>
                                </div>

                                <p class="text-xs text-gray-500">
                                    {{ $t->play_count }} plays
                                </p>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($recentSharedTracks->isNotEmpty())
                <div class="bg-white shadow rounded p-4 mb-4">
                    <h3 class="text-lg font-semibold mb-3">💿 Shared with you</h3>
                    @foreach($recentSharedTracks as $track)
                        <div class="mb-2 text-xs">
                            <a href="{{ route('tracks.show', $track) }}"
                                class="font-semibold text-indigo-600 hover:underline">
                                {{ $track->title }}
                            </a>
                            <span class="text-xs text-gray-500">
                                by {{ $track->owner->artist_name ?? 'Unknown' }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="bg-white shadow rounded p-4 mb-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold mb-3">🗣️ Your Recent Comments</h3>
                    <a href="{{ route('activity.index', ['tab' => 'comments']) }}"
                    class="text-xs text-indigo-600 hover:underline">
                        View all
                    </a>
                </div>
                @forelse ($recentComments as $comment)
                    <div class="border-b py-2 text-sm">
                        <div class="flex justify-between">
                            <a href="{{ route('tracks.show', $comment->track) }}" class="font-semibold text-indigo-600 hover:underline">
                                {{ $comment->track->title }}
                            </a>
                            <span class="text-xs text-gray-500">
                                {{ $comment->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <p>{{ $comment->body }}</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">You haven’t left any comments yet.</p>
                @endforelse
            </div>
            <div class="bg-white shadow rounded p-4 mb-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold mb-3">🎭 Your Recent Reactions</h3>
                    <a href="{{ route('activity.index', ['tab' => 'reactions']) }}"
                    class="text-xs text-indigo-600 hover:underline">
                        View all
                    </a>
                </div>

                @forelse ($recentReactions as $reaction)
                    <div class="border-b py-2 text-sm flex justify-between items-center">
                        <div>
                            <a href="{{ route('tracks.show', $reaction->track) }}" class="font-semibold text-indigo-600 hover:underline">
                                {{ $reaction->track->title }}
                            </a>
                            <p class="text-xs text-gray-500">
                                {{ $reaction->created_at->diffForHumans() }}
                            </p>
                        </div>
                        <div>
                            @php
                                $icons = [
                                    'like'    => '👍',
                                    'dislike' => '👎',
                                    'laugh'   => '😂',
                                    'wow'     => '😮',
                                    'sad'     => '😢',
                                ];
                            @endphp
                            <span class="text-xl">
                                {{ $icons[$reaction->type] ?? '❓' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">You haven’t reacted to any tracks yet.</p>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>
