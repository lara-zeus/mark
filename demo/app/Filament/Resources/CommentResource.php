<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommentResource\Pages\CreateComment;
use App\Filament\Resources\CommentResource\Pages\EditComment;
use App\Filament\Resources\CommentResource\Pages\ListComments;
use App\Filament\Resources\CommentResource\Pages\ViewComment;
use App\Models\Comment;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\IconEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use LaraZeus\Mark\Forms\Components\Mark;
use LaraZeus\Mark\Infolists\Components\MarkEntry;
use LaraZeus\Mark\Tables\Columns\MarkColumn;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->schema([
                Select::make('post_id')
                    ->relationship('post', 'title')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'email')
                    ->required(),
                Textarea::make('text')
                    ->required()
                    ->columnSpanFull(),
                Mark::make('rating')
                    ->live()
                    ->rating(),
                Mark::make('like')
                    ->live()
                    ->like(),
                Mark::make('bookmark')
                    ->live()
                    ->bookmark(),
                Radio::make('feedback')
                    ->visible(fn ($get) => $get('rating') === 1)
                    ->label('Like this post ?')
                    ->boolean()
                    ->inline()
                    ->inlineLabel(false)
                    ->saved(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name'),
                TextColumn::make('post.title')
                    ->wrap(),
                MarkColumn::make('rating')
                    ->rating(),
                MarkColumn::make('like')
                    ->like(),
                MarkColumn::make('bookmark')
                    ->bookmark(),
                //                MarkColumn::make('like')
                //                    ->relationship(stateColumn: 'value')
                //                    ->like(),
                //                MarkColumn::make('rating')
                //                    ->relationship(stateColumn: 'value')
                //                    ->rating(),
                //                MarkColumn::make('bookmark')
                //                    ->relationship()
                //                    ->bookmark(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            IconEntry::make('text')
                ->boolean(),
            MarkEntry::make('rating')
                ->rating(),
            MarkEntry::make('like')
                ->like(),
            MarkEntry::make('bookmark')
                ->bookmark(),
            //            MarkEntry::make('like')
            //                ->relationship(stateColumn: 'value')
            //                ->like(),
            //            MarkEntry::make('rating')
            //                ->relationship(stateColumn: 'value')
            //                ->rating(),
            //            MarkEntry::make('bookmark')
            //                ->relationship()
            //                ->bookmark(),

        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListComments::route('/'),
            'create' => CreateComment::route('/create'),
            'edit' => EditComment::route('/{record}/edit'),
            'view' => ViewComment::route('/{record}'),
        ];
    }
}
