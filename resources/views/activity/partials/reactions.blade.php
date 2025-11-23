@if($reactions->isEmpty())
    <p class="text-sm text-gray-500">You haven’t reacted to any tracks yet.</p>
@else
    @php
        $icons = [
            'like'    => '👍',
            'dislike' => '👎',
            'laugh'   => '😂',
            'wow'     => '😮',
            'sad'     => '😢',
        ];
    @endphp

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
            <tr class="border-b text-left text-gray-500">
                <th class="py-2 pr-4">Track</th>
                <th class="py-2 pr-4">Reaction</th>
                <th class="py-2 pr-4">Date</th>
            </tr>
            </thead>
            <tbody>
            @foreach($reactions as $reaction)
                <tr class="border-b last:border-0">
                    <td class="py-2 pr-4">
                        <a href="{{ route('tracks.show', $reaction->track) }}"
                           class="text-indigo-600 hover:underline">
                            {{ $reaction->track->title ?? 'Deleted Track' }}
                        </a>
                    </td>
                    <td class="py-2 pr-4">
                        {{ $icons[$reaction->type] ?? '❔' }}
                        {{ ucfirst($reaction->type) }}
                    </td>
                    <td class="py-2 pr-4">
                        {{ $reaction->created_at->diffForHumans() }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $reactions->appends(['tab' => 'reactions', 'search' => request('search')])->links() }}
    </div>
@endif
