<x-guest-layout>

    <div class="max-w-3xl mx-auto mt-10 bg-white p-6 shadow rounded text-center">
        <h1 class="text-2xl font-bold mb-3 ">DEMONstration</h1>
        <p class="mb-4 ">This is a closed alpha test. Please sign in to continue.</p>

        <div class="flex gap-3 justify-center">
            <a href="{{ route('login') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Log in</a>
            <a href="{{ route('register') }}" class="bg-gray-200 px-4 py-2 rounded">Register</a>
        </div>

</x-guest-layout>
