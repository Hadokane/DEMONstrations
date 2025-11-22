<form method="POST" action="{{ route('tracks.upload') }}" enctype="multipart/form-data" class="space-y-4">
    @csrf

    <div>
        <x-input-label for="title" value="Track Title" />
        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" 
            :value="old('title')" required />
        <x-input-error class="mt-2" :messages="$errors->get('title')" />
    </div>

    <div>
        <x-input-label for="audio" value="Audio File" />
        <input id="audio" name="audio" type="file" 
            class="mt-1 block w-full border-gray-300 rounded" required />
        <p class="text-xs text-gray-500 mt-1">
            Accepted: MP3 / WAV – max 25MB.
        </p>
        <x-input-error class="mt-2" :messages="$errors->get('audio')" />
    </div>

    <div>
        <x-input-label for="visibility" value="Visibility" />
        <select id="visibility" name="visibility"
            class="mt-1 block w-full border-gray-300 rounded">
            <option value="public" {{ old('visibility') === 'public' ? 'selected' : '' }}>Public</option>
            <option value="private" {{ old('visibility') === 'private' ? 'selected' : '' }}>Private</option>
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('visibility')" />
    </div>

    <div class="flex justify-end">
        <x-primary-button>
            Upload Track
        </x-primary-button>
    </div>
</form>