<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExerciseSubmissionResource\Pages;
use App\Filament\Resources\ExerciseSubmissionResource\RelationManagers;
use App\Models\ExerciseSubmission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExerciseSubmissionResource extends Resource
{
    protected static ?string $model = ExerciseSubmission::class;

    protected static ?string $navigationIcon = 'heroicon-m-archive-box';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListExerciseSubmissions::route('/'),
            'create' => Pages\CreateExerciseSubmission::route('/create'),
            'edit' => Pages\EditExerciseSubmission::route('/{record}/edit'),
        ];
    }
}
