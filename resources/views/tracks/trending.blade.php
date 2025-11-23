<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Trending Tracks
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold">🔥 Trending Tracks</h1>

            <form method="GET" action="{{ route('tracks.trending') }}" class="flex space-x-2">
                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Search title or artist..."
                       class="border-gray-300 rounded px-3 py-1 text-sm">
                <x-primary-button class="text-sm">
                    Search
                </x-primary-button>
            </form>
        </div>

        <div class="bg-white shadow sm:rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                        Cover
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                        Title
                    </th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                        Artist
                    </th>
                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">
                        Plays
                    </th>
                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">
                        Visibility
                    </th>
                    <th class="px-4 py-2"></th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($tracks as $track)
                    <tr>
                        <td class="px-4 py-2 text-sm">
                            <a href="{{ route('tracks.show', $track) }}">
                                <x-cover-image :track="$track" size="sm" />
                            </a>
                        </td>
                        <td class="px-4 py-2 text-sm">
                            <a href="{{ route('tracks.show', $track) }}"
                               class="text-indigo-600 hover:underline">
                                {{ $track->title }}
                            </a>
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-700">
                            {{ $track->owner->artist_name ?? 'Unknown artist' }}
                        </td>
                        <td class="px-4 py-2 text-sm text-center">
                            {{ $track->play_count }}
                        </td>
                        <td class="px-4 py-2 text-sm text-center">
                            <span class="px-2 py-0.5 text-xs rounded
                                    {{ $track->visibility === 'private'
                                        ? 'bg-gray-800 text-white'
                                        : 'bg-emerald-600 text-white' }}">
                                {{ ucfirst($track->visibility) }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-right text-sm">
                            <a href="{{ route('tracks.show', $track) }}"
                               class="text-indigo-600 hover:underline">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500">
                            No tracks found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $tracks->links() }}
        </div>
    </div>
</x-app-layout>
