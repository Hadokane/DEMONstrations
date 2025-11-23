@if($tracks->isEmpty())
    <p class="text-sm text-gray-500">No tracks have been shared with you yet.</p>
@else
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 uppercase tracking-wide">
            <tr class="border-b text-left text-gray-500">
                <th class="py-2 pr-4">Title</th>
                <th class="py-2 pr-4">Artist</th>
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
                    <td class="py-2 pr-4">{{ $track->owner->artist_name }}</td>
                    <td class="py-2 pr-4">{{ $track->created_at->diffForHumans() }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $tracks->appends(['tab' => 'shared', 'search' => request('search')])->links() }}
    </div>
@endif
