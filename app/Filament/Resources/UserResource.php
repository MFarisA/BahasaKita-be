<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-s-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('email')->email()->required(),
                TextInput::make('password')
                    ->password()
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn($record) => $record === null),
                TextInput::make('profile.level')
                    ->label('Level')
                    ->numeric()
                    ->dehydrated(),
                TextInput::make('profile.xp')
                    ->label('XP')
                    ->numeric()
                    ->dehydrated(),
                FileUpload::make('profile.photo')
                    ->label('Photo')
                    ->dehydrated()
                    ->image()
                    ->imageEditor()
                    ->imageCropAspectRatio('1:1')
                    ->maxSize(2048)
                    ->disk('public')
                    ->directory('profile-photos')
                    ->visibility('public')
                    ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml']),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('profile.level')
                    ->label('Level')
                    ->default('-'),
                TextColumn::make('profile.xp')
                    ->label('XP')
                    ->default('-'),

                ImageColumn::make('profile.photo')
                    ->label('Photo')
                    ->circular()
                    ->size(40)
                    ->getStateUsing(function ($record) {
                        if ($record->profile && $record->profile->photo) {
                            return asset("storage/{$record->profile->photo}");
                        }
                        return null;
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->with('profile');
    }
}
