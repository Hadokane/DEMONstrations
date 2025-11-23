@if($comments->isEmpty())
    <p class="text-sm text-gray-500">You haven’t left any comments yet.</p>
@else
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
            <tr class="border-b text-left text-gray-500">
                <th class="py-2 pr-4">On Track</th>
                <th class="py-2 pr-4">Comment</th>
                <th class="py-2 pr-4">At</th>
                <th class="py-2 pr-4">Date</th>
            </tr>
            </thead>
            <tbody>
            @foreach($comments as $comment)
                <tr class="border-b last:border-0 align-top">
                    <td class="py-2 pr-4">
                        <a href="{{ route('tracks.show', $comment->track) }}"
                           class="text-indigo-600 hover:underline">
                            {{ $comment->track->title ?? 'Deleted Track' }}
                        </a>
                    </td>
                    <td class="py-2 pr-4">
                        {{ $comment->body }}
                    </td>
                    <td class="py-2 pr-4">
                        {{ $comment->timestamp_ms
                            ? round($comment->timestamp_ms / 1000, 1) . 's'
                            : '-' }}
                    </td>
                    <td class="py-2 pr-4">
                        {{ $comment->created_at->diffForHumans() }}
                    </td>

                    @if(auth()->id() === $comment->user_id || auth()->user()->is_admin)
                        <td class="px-3 py-2 text-right">
                            <form method="POST"
                                action="{{ route('comments.destroy', $comment) }}"
                                class="inline"
                                onsubmit="return confirm('Delete this comment?');">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="text-red-600 hover:underline text-sm">
                                    Delete
                                </button>
                            </form>
                        </td>
                    @endif

                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $comments->appends(['tab' => 'comments', 'search' => request('search')])->links() }}
    </div>
@endif
