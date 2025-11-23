<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit User
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white shadow sm:rounded-lg p-6">

            <div class="mb-2">   
                <a href="{{ route('admin.users.index') }}"
                class="text-sm text-gray-600 hover:underline">
                    ← Back to users
                </a>
            </div>

            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold mb-4">Edit {{ $user->artist_name }}</h1>
            </div>

            @if (session('status'))
                <div class="mb-4 text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif
            
            <div>
                @include('admin.users.partials.edit-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
