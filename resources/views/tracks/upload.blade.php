<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ ('Upload Track') }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('dashboard') }}"
               class="text-sm text-gray-600 hover:underline">
                ← Back to dashboard
            </a>
        </div>

        @if(session('status'))
            <div class="mb-4 text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif
        
        <h1 class="text-2xl font-bold mb-4">Upload Track</h1>
        
        @include('tracks.partials.upload-track-form')
    </div>
</x-app-layout>
