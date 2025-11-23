<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Activity Manager
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">

                @php
                    $tabs = [
                        'tracks'    => 'Your Tracks',
                        'comments'  => 'Your Comments',
                        'reactions' => 'Your Reactions',
                        'shared'    => 'Shared With You',
                    ];
                @endphp

                <div class="border-b mb-4 flex space-x-4">
                    @foreach($tabs as $key => $label)
                        <a href="{{ route('activity.index', ['tab' => $key, 'search' => $search]) }}"
                           class="pb-2 border-b-2 text-sm
                                  {{ $tab === $key ? 'border-indigo-600 text-indigo-600 font-semibold'
                                                   : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>

                <form method="GET"
                    action="{{ route('activity.index') }}"
                    class="mb-4 grid gap-2 sm:flex sm:items-center">

                    <input type="hidden" name="tab" value="{{ $tab }}">

                    <input type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search {{ $tab }}..."
                        class="border-gray-300 rounded w-full sm:w-64 text-sm">

                    @if($tab === 'tracks')
                        <select name="filter"
                                class="border-gray-300 rounded text-sm">
                            <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>All</option>
                            <option value="mine" {{ $filter === 'mine' ? 'selected' : '' }}>My uploads</option>
                            <option value="public" {{ $filter === 'public' ? 'selected' : '' }}>My public tracks</option>
                            <option value="private" {{ $filter === 'private' ? 'selected' : '' }}>My private tracks</option>
                        </select>

                        <select name="sort"
                                class="border-gray-300 rounded text-sm">
                            <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest</option>
                            <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Oldest</option>
                            <option value="popularity" {{ $sort === 'popularity' ? 'selected' : '' }}>Most played</option>
                            <option value="reactions" {{ $sort === 'reactions' ? 'selected' : '' }}>Most reactions</option>
                        </select>
                    @endif

                    <x-primary-button>Apply</x-primary-button>

                    @if($search || ($tab === 'tracks' && ($filter !== 'all' || $sort !== 'newest')))
                        <a href="{{ route('activity.index', ['tab' => $tab]) }}"
                        class="text-xs text-gray-500 underline ml-1">
                            Reset
                        </a>
                    @endif
                </form>

                @if ($tab === 'tracks')
                    @include('activity.partials.tracks', ['tracks' => $items])
                @elseif ($tab === 'comments')
                    @include('activity.partials.comments', ['comments' => $items])
                @elseif ($tab === 'shared')
                    @include('activity.partials.shared', ['tracks' => $items])
                @else
                    @include('activity.partials.reactions', ['reactions' => $items])
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
