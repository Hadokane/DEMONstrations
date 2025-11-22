<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Users') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
        <div class="mb-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold">Users</h1>
            <a href="{{ route('admin.users.create') }}"
               class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                New User
            </a>
        </div>

        @if (session('status'))
            <div class="mb-4 text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-white shadow sm:rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Artist</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Tracks</th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Comments</th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Admin</th>
                        <th class="px-4 py-2"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ $user->artist_name }}</td>
                            <td class="px-4 py-2 text-sm text-gray-500">{{ $user->name }}</td>
                            <td class="px-4 py-2 text-sm text-gray-500">{{ $user->email }}</td>
                            <td class="px-4 py-2 text-sm text-center">{{ $user->tracks_count }}</td>
                            <td class="px-4 py-2 text-sm text-center">{{ $user->comments_count }}</td>
                            <td class="px-4 py-2 text-sm text-center">
                                @if($user->is_admin)
                                    <span class="px-2 py-1 text-xs rounded bg-indigo-100 text-indigo-800">Admin</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-right space-x-2">
                                <a href="{{ route('admin.users.show', $user) }}"
                                   class="text-indigo-600 hover:underline text-sm">
                                    View
                                </a>
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
    </div>
</x-app-layout>
