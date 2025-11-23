@props(['track'])

<span class="text-xs inline-flex items-center px-2 py-0.5 rounded
    {{ $track->visibility === 'private'
        ? 'bg-gray-800 text-white'
        : 'bg-emerald-600 text-white' }}">
    {{ ucfirst($track->visibility) }}
</span>
