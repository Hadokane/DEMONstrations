<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ ('User Details') }}
        </h2>
    </x-slot>

    <div class="flex items-center justify-between m-1">
        <a href="{{ route('admin.users.index') }}"
        class="text-sm text-gray-600 hover:underline">
            ← Back to users
        </a>
    </div>

    <div class="max-w-5xl mx-auto py-8 sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white shadow sm:rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-2">{{ $user->artist_name }}</h1>
            <p class="text-gray-600 mb-1">Real Name: {{ $user->name }}</p>
            <p class="text-gray-600 mb-1">Email: {{ $user->email }}</p>
            <p class="text-gray-600 mb-1">
                Role:
                @if($user->is_admin)
                    <span class="px-2 py-1 text-xs rounded bg-indigo-100 text-indigo-800">Admin</span>
                @else
                    <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-700">User</span>
                @endif
            </p>
            <p class="text-gray-600 mb-1">Joined: {{ $user->created_at->format('j F, Y') }}</p>
                        <div class="space-x-2 mt-4">
                <a href="{{ route('admin.users.edit', $user) }}"
                class="inline-flex items-center px-3 py-1.5 text-sm rounded bg-indigo-600 text-white hover:bg-indigo-700">
                    Edit
                </a>

                @if(auth()->id() !== $user->id)
                    <form action="{{ route('admin.users.destroy', $user) }}"
                        method="POST"
                        class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center px-3 py-1.5 text-sm rounded bg-red-600 text-white hover:bg-red-700"
                                onclick="return confirm('Delete this user?')">
                            Delete
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="bg-white shadow sm:rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-3">Tracks ({{ $user->tracks->count() }})</h2>
            @forelse($user->tracks as $track)
                <div class="border-b py-2">
                    <a href="{{ route('tracks.show', $track) }}" class="font-medium text-indigo-600 hover:underline">
                        {{ $track->title }}
                    </a>
                    <span class="ml-2 text-xs px-2 py-0.5 rounded 
                        {{ $track->visibility === 'private' ? 'bg-gray-800 text-white' : 'bg-emerald-600 text-white' }}">
                        {{ ucfirst($track->visibility) }}
                    </span>
                    <p class="text-xs text-gray-500">
                        Plays: {{ $track->plays->count() }},
                        Comments: {{ $track->comments->count() }},
                        Reactions: {{ $track->reactions->count() }}
                    </p>
                </div>
            @empty
                <p class="text-sm text-gray-500">No tracks uploaded.</p>
            @endforelse
        </div>

        <div class="bg-white shadow sm:rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-3">Comments ({{ $user->comments->count() }})</h2>
            @forelse ($user->comments as $comment)
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

                    <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}" class="mt-1">
                        @csrf
                        @method('DELETE')
                        <button class="text-xs text-red-600 hover:underline">
                            Delete Comment
                        </button>
                    </form>
                </div>
            @empty
                <p class="text-sm text-gray-500">No comments from this user yet.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
