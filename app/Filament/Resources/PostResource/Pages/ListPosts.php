<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return[
            'All'=> Tab::make()->icon('heroicon-o-rectangle-stack'),
            'published'=> Tab::make()
            ->icon('heroicon-o-eye')
            ->modifyQueryUsing(fn(Builder $query)=>$query->where('published' , true)),

            'unpublished'=> Tab::make()
            ->icon('heroicon-o-eye-slash')
            ->modifyQueryUsing(fn(Builder $query)=>$query->where('published' , false)),

        ];
    }
}
