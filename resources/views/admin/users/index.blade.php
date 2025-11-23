<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ ('Manage Users') }}
        </h2>
    </x-slot>

<div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">

    <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h1 class="text-2xl font-bold">Users</h1>

        <div class="flex flex-col sm:flex-row gap-3 sm:items-center">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-2">
                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Search users…"
                       class="border border-gray-300 rounded px-3 py-1 text-sm">

                <select name="sort"
                        class="border border-gray-300 rounded px-2 py-1 text-sm">
                    <option value="artist"   {{ $sort === 'artist' ? 'selected' : '' }}>Artist (A–Z)</option>
                    <option value="tracks"   {{ $sort === 'tracks' ? 'selected' : '' }}>Most Tracks</option>
                    <option value="comments" {{ $sort === 'comments' ? 'selected' : '' }}>Most Comments</option>
                </select>

                <button class="bg-gray-800 text-white px-3 py-1 rounded text-sm">
                    Apply
                </button>
            </form>

            <a href="{{ route('admin.users.create') }}"
               class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 text-sm text-center">
                New User
            </a>
        </div>
    </div>   

    @if (session('status'))
        <div class="mb-4 text-sm text-green-600">
            {{ session('status') }}
        </div>
    @endif

    <div class="bg-white shadow sm:rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 text-gray-500 uppercase tracking-wide">
            <tr class="border-b text-left text-gray-500">
                <th class="px-4 py-2">Artist</th>
                <th class="px-4 py-2">Name</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2 text-center">Tracks</th>
                <th class="px-4 py-2 text-center">Comments</th>
                <th class="px-4 py-2 text-center">Admin</th>
                <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($users as $user)
                    <tr>
                        <td class="px-4 py-2 text-sm">
                            <a href="{{ route('admin.users.show', $user) }}"
                            class="text-indigo-600 hover:underline">
                                {{ $user->artist_name }}
                            </a>
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-500">{{ $user->name }}</td>
                        <td class="px-4 py-2 text-sm text-gray-500">{{ $user->email }}</td>

                        <td class="px-4 py-2 text-sm text-center">
                            <a href="{{ route('admin.users.show', $user) }}#tracks"
                            class="text-indigo-600 hover:underline">
                                {{ $user->tracks_count }}
                            </a>
                        </td>

                        <td class="px-4 py-2 text-sm text-center">
                            <a href="{{ route('admin.users.show', $user) }}#comments"
                            class="text-indigo-600 hover:underline">
                                {{ $user->comments_count }}
                            </a>
                        </td>
                        
                        <td class="px-4 py-2 text-sm text-center">
                            @if($user->is_admin)
                                <span class="px-2 py-1 text-xs rounded bg-indigo-100 text-indigo-800">Admin</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-right space-x-2">
                                <a href="{{ route('admin.users.edit', $user) }}"
                                    class="text-blue-600 hover:underline text-sm">
                                    Edit
                                </a>
                            @if(auth()->id() !== $user->id)
                                <form action="{{ route('admin.users.destroy', $user) }}"
                                        method="POST"
                                        class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:underline text-sm"
                                            onclick="return confirm('Delete this user?')">
                                        Delete
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
</x-app-layout>
