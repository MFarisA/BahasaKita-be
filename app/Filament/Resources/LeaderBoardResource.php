<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaderBoardResource\Pages;
use App\Filament\Resources\LeaderBoardResource\RelationManagers;
use App\Models\LeaderBoard;
use App\Models\Profile;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LeaderBoardResource extends Resource
{
    protected static ?string $model = Profile::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';
    protected static ?string $title = 'Leader Board';
    protected static ?string $label = 'Leader Board';
    protected static ?string $pluralLabel = 'Leader Boards';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->required(),

                TextInput::make('xp')
                    ->integer()
                    ->label('Points')
                    ->required()
                    ->default(1),

                TextInput::make('level')
                    ->label('Level')
                    ->integer()
                    ->required()
                    ->default(1),

                FileUpload::make('photo')
                    ->label('Photo')
                    ->dehydrated()
                    ->image()
                    ->imageEditor()
                    ->imageCropAspectRatio('1:1')
                    ->maxSize(2048)
                    ->disk('public')
                    ->directory('profile-photos')
                    ->visibility('public')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp']),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                return $query->whereHas('user', function ($query) {
                    $query->where('email', '!=', 'adminSuper@x.com');
                });
            })
            ->defaultSort('xp', 'desc')
            ->columns([
                TextColumn::make('rank')
                    ->label('Rank')
                    ->rowIndex()
                    ->sortable(false),

                TextColumn::make('user.name')
                    ->label('User')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('xp')
                    ->label('Points')
                    ->sortable(),

                TextColumn::make('level')
                    ->label('Level')
                    ->sortable(),

                ImageColumn::make('photo')
                    ->label('Photo')
                    ->circular()
                    ->size(40)
                    ->getStateUsing(fn($record) => $record->photo ? asset("storage/{$record->photo}") : null),
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
            'index' => Pages\ListLeaderBoards::route('/'),
            'create' => Pages\CreateLeaderBoard::route('/create'),
            'edit' => Pages\EditLeaderBoard::route('/{record}/edit'),
        ];
    }
}
