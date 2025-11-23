<form method="POST" action="{{ route('tracks.update', $track) }}" enctype="multipart/form-data" class="space-y-4">
    @csrf
    @method('PATCH')

    <div>
        <x-input-label for="title" value="Title" />
        <x-text-input id="title"
                        name="title"
                        type="text"
                        class="mt-1 block w-full"
                        :value="old('title', $track->title)"
                        required />
        <x-input-error class="mt-2" :messages="$errors->get('title')" />
    </div>

    <div>
        <x-input-label for="visibility" value="Visibility" />
        <select id="visibility"
                name="visibility"
                class="mt-1 block w-full border-gray-300 rounded">
            <option value="public" {{ old('visibility', $track->visibility) === 'public' ? 'selected' : '' }}>
                Public
            </option>
            <option value="private" {{ old('visibility', $track->visibility) === 'private' ? 'selected' : '' }}>
                Private
            </option>
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('visibility')" />
    </div>

    <div>
        <x-input-label for="audio" value="Replace audio file (optional)" />
        <input id="audio"
                name="audio"
                type="file"
                class="mt-1 block w-full border-gray-300 rounded" />
        <p class="text-xs text-gray-500 mt-1">
            Leave this empty to keep the current audio.
        </p>
        <x-input-error class="mt-2" :messages="$errors->get('audio')" />
    </div>

    <div>
        <x-input-label value="Current Cover" />
        <a href="{{ route('tracks.show', $track) }}">
            <x-cover-image :track="$track" size="lg" />
        </a>
    </div>

    <div>
        <x-input-label for="cover_image" value="Replace Cover Image" />
        <input id="cover_image" name="cover_image" type="file"
            class="mt-1 block w-full border-gray-300 rounded" accept="image/*">
        <x-input-error class="mt-2" :messages="$errors->get('cover_image')" />
    </div>

    <div class="flex justify-between items-center mt-6">
        <x-primary-button>
            Save Changes
        </x-primary-button>
    </div>
</form>