@if($tracks->isEmpty())
    <p class="text-sm text-gray-500">You haven’t uploaded any tracks yet.</p>
@else
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
            <tr class="border-b text-left text-gray-500">
                <th class="py-2 pr-4">Title</th>
                <th class="py-2 pr-4">Visibility</th>
                <th class="py-2 pr-4">Plays</th>
                <th class="py-2 pr-4">Reactions</th>
                <th class="py-2 pr-4">Comments</th>
                <th class="py-2 pr-4">Created</th>
                <th class="py-2"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($tracks as $track)
                <tr class="border-b last:border-0">
                    <td class="py-2 pr-4">
                        <a href="{{ route('tracks.show', $track) }}" class="text-indigo-600 hover:underline">
                            {{ $track->title }}
                        </a>
                    </td>
                    <td class="py-2 pr-4">
                        <span class="px-2 py-0.5 rounded text-xs
                            {{ $track->visibility === 'public'
                                ? 'bg-emerald-100 text-emerald-700'
                                : 'bg-gray-800 text-white' }}">
                            {{ ucfirst($track->visibility) }}
                        </span>
                    </td>
                    <td class="py-2 pr-4">{{ $track->plays_count }}</td>
                    <td class="py-2 pr-4">{{ $track->reactions_count }}</td>
                    <td class="py-2 pr-4">{{ $track->comments_count }}</td>
                    <td class="py-2 pr-4">{{ $track->created_at->diffForHumans() }}</td>
                    <td class="py-2 text-right space-x-2">
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
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $tracks->appends(['tab' => 'tracks', 'search' => request('search')])->links() }}
    </div>
@endif
