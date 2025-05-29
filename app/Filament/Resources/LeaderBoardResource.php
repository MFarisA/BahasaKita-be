<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaderBoardResource\Pages;
use App\Filament\Resources\LeaderBoardResource\RelationManagers;
use App\Models\LeaderBoard;
use App\Models\LessonProgress;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeaderBoardResource extends Resource
{
    protected static ?string $model = LessonProgress::class;
    protected static ?string $title = 'Leader Board';
    protected static ?string $label = 'Leader Board';
    protected static ?string $pluralLabel = 'Leader Boards';

    protected static ?string $navigationIcon = 'heroicon-o-trophy';
    public static function getModelLabel(): string
    {
        return static::$label;
    }

    public static function getPluralModelLabel(): string
    {
        return static::$pluralLabel;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('Id user')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),
                TextInput::make('score')
                    ->label('Skor')
                    ->numeric()
                    ->required(),
                textInput::make('is_completed')
                    ->label('Selesai'),
                DateTimePicker::make('created_at')
                    ->label('Progress Date')
                    ->disabled(),
                DateTimePicker::make('updated_at')
                    ->label('Last Updated')
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('User Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('score')
                    ->label('Score')
                    ->sortable()
                    ->numeric(),
                BooleanColumn::make('is_completed')
                    ->label('Completed')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Progress Date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable(),
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
