<form action="{{ route('tracks.destroy', $track) }}"
        method="POST"
        onsubmit="return confirm('Delete this track? This action cannot be undone.');">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="inline-flex items-center px-3 py-2 text-sm 
                        rounded text-white bg-red-600 hover:bg-red-700">
                Delete Track
        </button>
</form>