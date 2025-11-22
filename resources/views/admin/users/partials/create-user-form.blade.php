<form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
    @csrf

    <div>
        <x-input-label for="artist_name" value="Artist Name" />
        <x-text-input id="artist_name" name="artist_name" type="text" class="mt-1 block w-full"
                        :value="old('artist_name')" required />
        <x-input-error class="mt-2" :messages="$errors->get('artist_name')" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <x-input-label for="first_name" value="First Name" />
            <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full"
                            :value="old('first_name')" required />
            <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
        </div>

        <div>
            <x-input-label for="last_name" value="Last Name" />
            <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full"
                            :value="old('last_name')" required />
            <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
        </div>
    </div>

    <div>
        <x-input-label for="email" value="Email" />
        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                        :value="old('email')" required />
        <x-input-error class="mt-2" :messages="$errors->get('email')" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required />
            <x-input-error class="mt-2" :messages="$errors->get('password')" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Confirm Password" />
            <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                            class="mt-1 block w-full" required />
        </div>
    </div>

    <div class="flex items-center">
        <input id="is_admin" name="is_admin" type="checkbox" value="1" class="rounded border-gray-300">
        <label for="is_admin" class="ml-2 text-sm text-gray-700">Make this user an admin</label>
    </div>

    <div class="flex justify-end">
        <x-primary-button>Create User</x-primary-button>
    </div>
</form>