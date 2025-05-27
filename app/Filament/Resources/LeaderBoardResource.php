<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaderBoardResource\Pages;
use App\Filament\Resources\LeaderBoardResource\RelationManagers;
use App\Models\LeaderBoard;
use App\Models\LessonProgress;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
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

    protected static ?string $navigationIcon = 'heroicon-s-chart-bar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('user.name')
                    ->label('User Name')
                    ->required()
                    ->disabled(),
                TextInput::make('lesson.title'),
                TextInput::make('score')
                    ->label('Score')
                    ->numeric()
                    ->required(),
                textInput::make('is_completed')
                    ->label('Completed'),
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
                TextColumn::make('lesson.title')
                    ->label('Lesson Title')
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
