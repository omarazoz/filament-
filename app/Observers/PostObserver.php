<?php

namespace App\Observers;

use App\Models\Post;
use App\Models\User;
use Filament\Notifications\Notification ;
use Filament\Notifications\Actions\Action;


class PostObserver
{
    public function created(Post $post): void
    {
        $user = User::all();

        if ($user) {
            Notification::make()
                ->title('New Post Created')
                ->body("Post '{$post->title}' has been created. <br> And His Content '{$post->content}'")
                ->actions([
                Action::make('View')
                    ->icon('heroicon-o-eye')
                    ->button()
                    ->url(route('filament.admin.resources.posts.view', $post->id)),

                Action::make('Mark as Read')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->button()
                    ->markAsRead()
            ])
                ->success()
                ->sendToDatabase($user);
        }
    }

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post): void
    {
        $users = User::all();

    if ($users->isNotEmpty()) {
        Notification::make()
            ->title('Post Updated')
            ->body("Post '{$post->title}' has been updated. <br> New Content: '{$post->content}'")
            ->actions([
                Action::make('View')
                    ->icon('heroicon-o-eye')
                    ->button()
                    ->url(route('filament.admin.resources.posts.view', $post->id)),
                Action::make('Mark as Read')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->button()
                    ->markAsRead()
                ])
            ->success()
            ->sendToDatabase($users);
    }
    }

    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
        $users = User::all();

        if ($users->isNotEmpty()) {
            Notification::make()
                ->title('Post Deleted')
                ->body("Post '{$post->title}' has been Deleted.")
                ->actions([
                    Action::make('Mark as Read')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->button()
                        ->markAsRead()
                ])
                ->success()
                ->sendToDatabase($users);

        }
    }

    /**
     * Handle the Post "restored" event.
     */
    public function restored(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "force deleted" event.
     */
    public function forceDeleted(Post $post): void
    {
        //
    }
}
