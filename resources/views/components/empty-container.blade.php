@props(['title', 'message' => null])

<div class="border-t border-gray-200 mt-4 py-6 text-center text-sm text-gray-500">
    <h2 class="font-semibold mb-1">{{ $title }}</h2>
    @if($message)
        <p class="mb-3">{{ $message }}</p>
    @endif

    <img src="{{ asset('img/empty-demon.png') }}"
         alt="Empty state illustration"
         class="mx-auto"
         width="120"
         height="120">
</div>
