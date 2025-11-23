<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ ('Notifications') }}
        </h2>
    </x-slot>

    <div class="max-w-5xl mx-auto py-8 sm:px-6 lg:px-8 space-y-6">

        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold">
                Notifications
                @if($unreadCount > 0)
                    <span class="ml-2 text-sm text-indigo-600">
                        ({{ $unreadCount }} unread)
                    </span>
                @endif
            </h1>

            @if($unreadCount > 0)
                <form method="POST" action="{{ route('notifications.readAll') }}">
                    @csrf
                    <x-primary-button>
                        Mark all as read
                    </x-primary-button>
                </form>
            @endif
        </div>

        <div class="bg-white shadow sm:rounded-lg overflow-hidden">
            @if($notifications->isEmpty())
                <p class="p-4 text-sm text-gray-500">
                    No notifications yet.
                </p>
            @else
                <ul class="divide-y divide-gray-200">
                    @foreach($notifications as $notification)
                        <li class="p-4 flex items-start justify-between
                            {{ $notification->isUnread() ? 'bg-indigo-50' : 'bg-white' }}">
                            <div>
                                <p class="font-semibold text-sm text-gray-900">
                                    {{ $notification->title }}
                                </p>
                                @if($notification->body)
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ $notification->body }}
                                    </p>
                                @endif
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $notification->created_at->diffForHumans() }}
                                    @if($notification->isUnread())
                                        · Unread
                                    @endif
                                </p>
                            </div>

                            <div class="ml-4 flex items-center space-x-2">
                                <form method="POST"
                                      action="{{ route('notifications.read', $notification) }}">
                                    @csrf
                                    <x-primary-button class="text-xs">
                                        View
                                    </x-primary-button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div class="px-4 py-3">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
