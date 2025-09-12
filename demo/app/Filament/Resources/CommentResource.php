<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommentResource\Pages;
use App\Models\Bookmark;
use App\Models\Comment;
use App\Models\Like;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use LaraZeus\Mark\Forms\Components\Mark;
use LaraZeus\Mark\Tables\Columns\MarkColumn;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\Select::make('post_id')
                    ->relationship('post', 'title')
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'email')
                    ->required(),
                Forms\Components\Textarea::make('text')
                    ->required()
                    ->columnSpanFull(),
                Mark::make('rating')
                    ->live()
                    ->relationship(stateColumn: 'value')
                    ->rating(),

                Mark::make('like')
                    ->live()
                    ->relationship(stateColumn: 'value')
                    ->like(),
                Mark::make('bookmark')
                    ->relationship()
                    ->live()
                    ->bookmark(),
                Forms\Components\Radio::make('feedback')
                    ->visible(fn ($get) => $get('rating') === '1')
                    ->label('Like this post ?')
                    ->boolean()
                    ->inline()
                    ->inlineLabel(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('post.title'),
                MarkColumn::make('like')
                    ->relationship(stateColumn: 'value')
                    ->like(),
                MarkColumn::make('rating')
                    ->relationship(stateColumn: 'value')
                    ->rating(),
                MarkColumn::make('bookmark')
                    ->relationship()
                    ->bookmark(),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComments::route('/'),
            'create' => Pages\CreateComment::route('/create'),
            'edit' => Pages\EditComment::route('/{record}/edit'),
        ];
    }
}
