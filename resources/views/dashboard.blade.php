<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </x-slot>

    <div class="container mx-auto mt-6">
        <h1 class="text-2xl font-bold mb-4">🎧 Your Dashboard</h1>


        @if(auth()->user()->is_admin)
            <form class="mt-6" method="POST" action="{{ route('tracks.upload') }}">
                @csrf
                <input name="title" class="border rounded p-2" placeholder="Track title">
                <button class="ml-2 px-3 py-2 rounded">Create</button>
            </form>
            <br />
        @endif

        @foreach($tracks as $track)
            <div class="bg-white shadow rounded p-4 mb-4">
                <h2 class="text-xl font-semibold"> 
                    <a href="{{ route('tracks.show', $track) }}">
                        💿 {{ $track->title }}
                    </a>
                </h2>
                <p class="text-gray-600">Artist: {{ $track->artist ?? 'Unknown' }}</p>

                @php 
                    $plays = $track->plays->count();
                    $likes = $track->likesCount();
                    $dislikes = $track->dislikesCount();
                    $laughs = $track->laughsCount();
                    $wows = $track->wowCount();
                    $sads = $track->sadCount();
                    $totalVotes = $likes + $dislikes;
                    $approval = round(($likes / $totalVotes) * 100);
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

                <p class="text-sm text-gray-500">Total Votes: {{ $totalVotes }}%</p>
                <p class="text-sm text-gray-500">Approval Rating: {{ $approval }}%</p>
                <p class="text-sm text-gray-500">Unique Listeners: {{ $track->plays->unique('user_id')->count() }}</p>
            </div>
        @endforeach
    </div>

</x-app-layout>
