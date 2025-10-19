<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;

class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'posts'; // must match Category::posts()

    public function form(Form $form): Form
    {
        return $form ->schema([
                Group::make()->schema([
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                ->unique(table: 'posts', column: 'slug', ignoreRecord: true)
                    ->required(),
                TagsInput::make('tags')
                        ->suggestions([
                            'C#',
                            '.net',
                            'php',
                            'laravel',
                            'javascript',
                        ]),
                ]),

                RichEditor::make('content')->required(),
                Toggle::make('published')
                    ->required(),
                FileUpload::make('thumbnail')
                        ->label('thumbnail')
                        ->image()->disk('public')
                        ->directory('thumbnail')
                        ->nullable(),
            ])->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->searchable()->toggleable(),
                TextColumn::make('title')->sortable()->searchable()->toggleable(),
                TextColumn::make('slug')->sortable()->searchable()->toggleable(),
                TextColumn::make('category.name')->sortable()->searchable()->toggleable(),
                ImageColumn::make('thumbnail')
                        ->label('Thumbnail')
                        ->getStateUsing(fn ($record) => $record->thumbnail ? asset('storage/' . $record->thumbnail) : null)
                        ->circular() // optional
                        ->size(50)->toggleable(),
                ToggleColumn::make('published')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(), // ✅ Correct version!
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
