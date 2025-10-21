<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Filament\Resources\PostResource\RelationManagers\UsersRelationManager;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
                ->schema([
                    Tabs::make('Post Details')->tabs([
                        Tab::make('Basic Info')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            TextInput::make('title')
                                ->required(),
                            TextInput::make('slug')
                                ->unique(table: 'posts', column: 'slug', ignoreRecord: true)
                                    ->required(),
                        ]),
                        Tab::make('Content')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            RichEditor::make('content')->required(),
                            FileUpload::make('thumbnail')
                                    ->label('thumbnail')
                                    ->image()->disk('public')
                                    ->directory('thumbnail')
                                    ->nullable(),
                        ]),
                        Tab::make('Meta')
                        ->icon('heroicon-o-tag')
                        ->schema([
                            TagsInput::make('tags')
                            ->suggestions([
                                'C#',
                                '.net',
                                'php',
                                'laravel',
                                'javascript',
                                'devops',
                                'C++',
                            ]),
                            Select::make('category_id')->relationship('category', 'name')
                                ->label('Category')
                                ->required(),
                            Toggle::make('published')
                                ->required(),
                ]),
                    ])

            ])->columns(1);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->searchable()->toggleable(),
                TextColumn::make('title')->sortable()->searchable()->toggleable(),
                TextColumn::make('slug')->sortable()->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('category.name')->sortable()->searchable()->toggleable(),
                ImageColumn::make('thumbnail')
                        ->label('Thumbnail')
                        ->getStateUsing(fn ($record) => $record->thumbnail ? asset('storage/' . $record->thumbnail) : null)
                        ->circular() // optional
                        ->size( 50)->toggleable(),
                ToggleColumn::make('published')->toggleable(),
            ])
            ->filters([
                // Filter::make('published')
                //     ->query(function(Builder $query){
                //         return $query->where('published', true);
                //     }),
                TernaryFilter::make('published')
                    ->trueLabel('Published')
                    ->falseLabel('Unpublished')
                    ->placeholder('All'),
                // Filter::make('unpublished')
                //     ->query(function($query){
                //         return $query->where('published' ,false);
                //     })
                SelectFilter::make('category_id')
                    ->Label('Category')
                    ->relationship( 'category','name')
                    ->searchable()->preload(),
                ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            UsersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'view' => Pages\ViewPost::route('/{record}'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
