<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </x-slot>

    <div class="container mx-auto mt-6">
        <h1 class="text-2xl font-bold mb-4">🎧 Your Dashboard</h1>

        @if(auth()->user()->is_admin)
            <form class="mb-6" method="POST" action="{{ route('tracks.upload') }}" enctype="multipart/form-data">
                @csrf
                <div class="grid gap-3 md:grid-cols-4">
                    <input name="title" class="border rounded p-2" placeholder="Track Title" required>
                    <input name="artist" class="border rounded p-2" placeholder="Artist">
                    <select name="visibility" class="border rounded p-2">
                        <option value="public">Public</option>
                        <option value="private">Private</option>
                    </select>
                    <input type="file" name="audio" accept="audio/*" class="border rounded p-2" required>
                </div>
                <button class="mt-3 bg-green-600 hover:bg-green-700 px-4 py-2 rounded">Upload track</button>
                @error('audio') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
            </form>
        @endif

        @foreach($tracks as $track)
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
                
                <p class="text-gray-600">Artist: {{ $track->artist ?? 'Unknown' }}</p>

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
            </div>
        @endforeach
    </div>

</x-app-layout>
