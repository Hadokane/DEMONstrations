<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            All Tracks
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold">🔥 Currently Trending</h1>

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
            <thead class="bg-gray-50 text-gray-500 uppercase tracking-wide">
                <tr class="border-b text-left text-gray-500">
                    <th class="py-2 pr-4">
                        Cover
                    </th>
                    <th class="py-2 pr-4">
                        Title
                    </th>
                    <th class="py-2 pr-4">
                        Artist
                    </th>
                    <th class="py-2 pr-4">
                        Plays
                    </th>
                    <th class="py-2 pr-4"></th>
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
