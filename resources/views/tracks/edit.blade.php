<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Track
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white shadow sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">Edit "{{ $track->title }}"</h1>

                <a href="{{ route('tracks.show', $track) }}"
                   class="text-sm text-gray-600 hover:underline">
                    ← Back to track
                </a>
            </div>

            @if (session('status'))
                <div class="mb-4 text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            @include('tracks.partials.edit-track-form', ['track' => $track])
            @include('tracks.partials.delete-track-form', ['track' => $track])
        </div>
    </div>
</x-app-layout>
