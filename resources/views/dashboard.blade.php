<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </x-slot>

    <div class="container mx-auto mt-6">
        <h1 class="text-2xl font-bold mb-4">🎧 Your Dashboard</h1>

        <div class="flex justify-end m-2">
            <a href="{{ route('tracks.upload.form') }}">
                <x-primary-button>
                    Upload Track
                </x-primary-button>
            </a>
        </div>

        <div class="mb-4 flex space-x-2">
            <a href="{{ route('dashboard', ['filter' => 'all']) }}"
            class="px-3 py-1 rounded text-sm {{ $filter === 'all' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                All
            </a>
            <a href="{{ route('dashboard', ['filter' => 'mine']) }}"
            class="px-3 py-1 rounded text-sm {{ $filter === 'mine' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                My Tracks
            </a>
            <a href="{{ route('dashboard', ['filter' => 'shared']) }}"
            class="px-3 py-1 rounded text-sm {{ $filter === 'shared' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                Shared with Me
            </a>
            <a href="{{ route('dashboard', ['filter' => 'public']) }}"
            class="px-3 py-1 rounded text-sm {{ $filter === 'public' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                Public Tracks
            </a>
            <a href="{{ route('dashboard', ['filter' => 'private']) }}"
            class="px-3 py-1 rounded text-sm {{ $filter === 'private' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                Private Tracks
            </a>
        </div>

        @foreach($tracks as $track)
            @php
                $canManage = auth()->id() === $track->user_id || auth()->user()->is_admin;
            @endphp

            <div class="bg-white shadow rounded p-4 mb-4">
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
                @endif  
            </div>
        @endforeach
    </div>
</x-app-layout>
