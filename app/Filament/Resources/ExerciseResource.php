<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExerciseResource\Pages;
use App\Filament\Resources\ExerciseResource\RelationManagers;
use App\Models\Exercise;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;


class ExerciseResource extends Resource
{
    protected static ?string $model = Exercise::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('lesson_id')
                    ->label('lesson')
                    ->relationship('lesson', 'title')
                    ->required(),

                Select::make('type')
                    ->options([
                        'multiple_choice' => 'Multiple Choice',
                    ])
                    ->required()
                    ->label('Type'),

                FileUpload::make('gambar')
                    ->label('Soal gambar')
                    ->image()
                    ->dehydrated()
                    ->imageEditor()
                    ->imageCropAspectRatio('1:1')
                    ->maxSize(2048)
                    ->disk('public')
                    ->directory('exercise-photos')
                    ->visibility('public')
                    ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml']),

                KeyValue::make('content')
                    ->label('Konten Soal')
                    ->keyLabel('Kunci')
                    ->valueLabel('Isi')
                    ->required(),
                    
                KeyValue::make('answer')
                    ->label('Jawaban Benar')
                    ->keyLabel('Kunci Jawaban')
                    ->valueLabel('Isi Jawaban')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('lesson.title')
                    ->label('Pelajaran')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'multiple_choice' => 'success',
                        default => 'gray',
                    }),

                ImageColumn::make('gambar')
                    ->label('Gambar')
                    ->size(50),

                TextColumn::make('content')
                    ->label('Konten')
                    ->formatStateUsing(fn($state) => Str::limit(json_encode($state), 50))
                    ->tooltip(fn($state) => json_encode($state))
                    ->wrap(),

                TextColumn::make('answer')
                    ->label('Jawaban')
                    ->formatStateUsing(fn($state) => Str::limit(json_encode($state), 50))
                    ->tooltip(fn($state) => json_encode($state))
                    ->wrap(),

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
            'index' => Pages\ListExercises::route('/'),
            'create' => Pages\CreateExercise::route('/create'),
            'edit' => Pages\EditExercise::route('/{record}/edit'),
        ];
    }
}
