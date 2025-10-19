<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class Notifications extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.notifications';
    protected static ?string $navigationLabel = 'Notifications';

    public $notifications;

    public function mount()
    {
        $this->notifications = Auth::user()
            ->notifications()
            ->latest()
            ->take(50)
            ->get();
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
            $this->mount(); // reload notifications
        }
    }
}
