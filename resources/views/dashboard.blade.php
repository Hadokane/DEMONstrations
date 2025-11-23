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
                <form method="GET" action="{{ route('dashboard') }}" class="mb-4 flex items-center space-x-2">
                    <input
                        type="text"
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Search tracks/artists..."
                        class="border rounded px-3 py-1 text-sm w-64"
                    >

                    <select name="sort" class="border rounded px-2 py-1 text-sm">
                        <option value="popularity" {{ ($sort ?? '') === 'popularity' ? 'selected' : '' }}>Most Plays</option>
                        <option value="reactions" {{ ($sort ?? '') === 'reactions' ? 'selected' : '' }}>Most Reactions</option>
                        <option value="newest"  {{ ($sort ?? '') === 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="oldest"  {{ ($sort ?? '') === 'oldest' ? 'selected' : '' }}>Oldest</option>
                    </select>

                    <input type="hidden" name="filter" value="{{ $filter }}">
                    <x-primary-button class="text-sm">
                        Apply
                    </x-primary-button>
                </form>
               
                <div class="mb-4 flex space-x-2">
                    <a href="{{ route('dashboard', ['filter' => 'all']) }}#tracks-list"
                    class="px-3 py-1 rounded text-sm {{ $filter === 'all' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                        All
                    </a>
                    <a href="{{ route('dashboard', ['filter' => 'mine']) }}#tracks-list"
                    class="px-3 py-1 rounded text-sm {{ $filter === 'mine' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                        My Tracks
                    </a>
                    <a href="{{ route('dashboard', ['filter' => 'shared']) }}#tracks-list"
                    class="px-3 py-1 rounded text-sm {{ $filter === 'shared' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                        Shared with Me
                    </a>
                    <a href="{{ route('dashboard', ['filter' => 'public']) }}#tracks-list"
                    class="px-3 py-1 rounded text-sm {{ $filter === 'public' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                        Public Tracks
                    </a>
                    <a href="{{ route('dashboard', ['filter' => 'private']) }}#tracks-list"
                    class="px-3 py-1 rounded text-sm {{ $filter === 'private' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                        Private Tracks
                    </a>
                </div>

                <div class="mt-4 mb-4">
                    {{ $tracks->links() }}
                </div>

                @foreach($tracks as $track)
                    @php
                        $canManage = auth()->id() === $track->user_id || auth()->user()->is_admin;
                    @endphp

                <div class="bg-white shadow rounded p-4 mb-4 flex">
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
                        <h2 class="text-xl font-semibold"> 
                            <a href="{{ route('tracks.show', $track) }}">
                                💿 {{ $track->title }}
                            </a>
                        </h2>

                        @if($track->visibility === 'private')
                            <span class="text-xs inline-flex items-center px-2 py-0.5 rounded bg-gray-800 text-white">Private</span>
                        @else
                            <span class="text-xs inline-flex items-center px-2 py-0.5 rounded bg-emerald-600 text-white">Public</span>
                        @endif  
                        
                        <p class="text-gray-600">Artist: {{ $track->owner->artist_name }}</p>

                        @php 
                            $plays = $track->plays->count();
                            $likes = $track->likesCount();
                            $dislikes = $track->dislikesCount();
                            $laughs = $track->laughsCount();
                            $wows = $track->wowCount();
                            $sads = $track->sadCount();
                            $totalVotes = $likes + $dislikes;
                            $approval   = $totalVotes > 0 ? round(($likes / $totalVotes) * 100) : null;
                            $comments = $track->comments->count()
                        @endphp

                        <p class="mt-2">
                            ▶️ Plays: {{ $plays }} | 
                            👍 Likes: {{ $likes }} | 
                            👎 Dislikes: {{ $dislikes }} | 
                            😄 Laughs: {{ $laughs }} | 
                            🤩 Wows: {{ $wows }} | 
                            🥹 Sads: {{ $sads }} | 
                            💬 Comments: {{ $comments }}
                        </p>

                        @if(!is_null($approval))
                            <p class="text-sm text-gray-500">Approval Rating: {{ $approval }}%</p>
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

                @if($tracks->isEmpty())
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
            <div>
                <div class="bg-white shadow rounded p-4 mb-4">
                    <h3 class="text-lg font-semibold mb-3">🗣️ Your Recent Comments</h3>
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
                    <h3 class="text-lg font-semibold mb-3">🎭 Your Recent Reactions</h3>
    
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

    </div>
</x-app-layout>
