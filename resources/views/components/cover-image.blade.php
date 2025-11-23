@props([
    'track',
    'size' => 'lg',
])

@php
    $sizes = [
        'lg' => 'w-48 h-48 mr-4',
        'sm' => 'w-10 h-10',
    ];
    $class = $sizes[$size] ?? $sizes['lg'];
@endphp
    @if($track->cover_image_path)
    <img src="{{ asset('storage/'.$track->cover_image_path) }}"
    class="{{ $class }} object-cover rounded"
    alt="Cover image for {{ $track->title }}">
    @else
    <div class="{{ $class }} bg-gray-200 flex items-center justify-center rounded text-gray-500 text-xs">
        No Cover
    </div>
    @endif
    