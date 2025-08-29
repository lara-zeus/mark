<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommentResource\Pages;
use App\Models\Bookmark;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Rating;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use LaraZeus\Mark\Forms\Components\Mark;

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
//                Forms\Components\ToggleButtons::make('text222')
//                    ->options([
//                        '12' => 12
//                    ])
//                    ->required()
//                    ->columnSpanFull(),
//                Forms\Components\Toggle::make('text2')
//                    ->required()
//                    ->columnSpanFull(),
//                Forms\Components\Checkbox::make('text3')
//                    ->columnSpanFull(),
//                Forms\Components\CheckboxList::make('text3')
//                    ->options([
//                        1,
//                        2
//                    ])
//                    ->columnSpanFull(),
//                Forms\Components\Checkbox::make('def'),
//                Mark::make('the mark')
//                ->icons([
//                    'heroicon-o-star',
//                    'heroicon-c-star'
//                ]),
//            Forms\Components\Select::make()
//            ->relationship(),
//            Tables\Actions\AttachAction::
//            Forms\Components\Grid::make()
//            ->relationship(),
//                Mark::make('like')
//                    ->relationship()
//                    ->like(),
//                Mark::make('bookmarks')
////                    ->relationship()
//                    ->bookmark()
//                    ->relationship(),
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
                    ->visible(fn($get) => $get('rating') === '1')
                    ->label('Like this post ?')
                    ->boolean()
                    ->inline()
                    ->inlineLabel(false)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('post.title'),
                Tables\Columns\TextColumn::make('text'),
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
