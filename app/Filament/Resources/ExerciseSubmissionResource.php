<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExerciseSubmissionResource\Pages;
use App\Filament\Resources\ExerciseSubmissionResource\RelationManagers;
use App\Models\ExerciseSubmission;
use Filament\Forms;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExerciseSubmissionResource extends Resource
{
    protected static ?string $model = ExerciseSubmission::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('Id user')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),

                Select::make('exercise_id')
                    ->label('Id soal')
                    ->relationship('exercise', 'id')
                    ->required(),

                KeyValue::make('submitted_answer')
                    ->label('Jawaban yang Diserahkan')
                    ->keyLabel('Kunci')
                    ->valueLabel('Nilai')
                    ->required(),

                Toggle::make('is_correct')
                    ->label('Jawaban Benar?')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama Pengguna')
                    ->searchable(),

                TextColumn::make('exercise.id')
                    ->label('Latihan'),

                IconColumn::make('is_correct')
                    ->boolean()
                    ->label('Jawaban Benar')
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-mark'),

                TextColumn::make('created_at')
                    ->label('Dibuat')
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
            'index' => Pages\ListExerciseSubmissions::route('/'),
            'create' => Pages\CreateExerciseSubmission::route('/create'),
            'edit' => Pages\EditExerciseSubmission::route('/{record}/edit'),
        ];
    }
}
