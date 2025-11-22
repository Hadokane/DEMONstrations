<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('admin.users.index') }}"
               class="text-sm text-gray-600 hover:underline">
                ← Back to users
            </a>
        </div>

        <h1 class="text-2xl font-bold mb-4">Edit {{ $user->artist_name }}</h1>

        @if (session('status'))
            <div class="mb-4 text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        @include('admin.users.partials.edit-user-form')
        
    </div>
</x-app-layout>
