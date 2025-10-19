<x-filament-panels::page>
    <div class="space-y-4">
        @forelse ($notifications as $notification)
            <div class="p-4 rounded-xl border border-gray-200 dark:border-gray-700
                        flex items-center justify-between"
                wire:key="notification-{{ $notification->id }}">
                <div>
                    <h2 class="font-semibold text-gray-800 dark:text-gray-100">
                        {{ $notification->data['title'] ?? 'Notification' }}
                    </h2>

                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ $notification->data['message'] ?? '' }}
                    </p>

                    <span class="text-xs text-gray-500">
                        {{ $notification->created_at->diffForHumans() }}
                    </span>
                </div>

                @if ($notification->read_at === null)
                    <x-filament::button color="primary" wire:click="markAsRead('{{ $notification->id }}')">
                        Mark as read
                    </x-filament::button>
                @else
                    <x-filament::badge color="success">Read</x-filament::badge>
                @endif
            </div>
        @empty
            <div class="text-gray-500 dark:text-gray-400">
                No notifications yet.
            </div>
        @endforelse
    </div>

</x-filament-panels::page>
