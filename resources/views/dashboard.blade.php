<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </x-slot>

    <div class="container mx-auto mt-6">
        <h1 class="text-2xl font-bold mb-4">🎧 Your Dashboard</h1>

        @foreach($tracks as $track)
            <div class="bg-white shadow rounded p-4 mb-4">
                <h2 class="text-xl font-semibold"> 
                    <a href="{{ route('tracks.show', $track) }}">
                        💿 {{ $track->title }}
                    </a>
                </h2>
                <p class="text-gray-600">Artist: {{ $track->artist ?? 'Unknown' }}</p>

                <p class="mt-2">
                    ▶️ Plays: {{ $track->plays->count() }} | 
                    👍 Likes: {{ $track->likesCount() }} | 
                    👎 Dislikes: {{ $track->dislikesCount() }} | 
                    😄 Laughs: {{ $track->laughsCount() }} | 
                    🤩 Wows: {{ $track->wowCount() }} | 
                    🥹 Sads: {{ $track->sadCount() }} | 
                    💬 Comments: {{ $track->comments->count() }}
                </p>

                <p class="text-sm text-gray-500 mt-1">
                    Unique listeners: {{ $track->plays->unique('user_id')->count() }}
                </p>          </div>
        @endforeach
    </div>

</x-app-layout>
