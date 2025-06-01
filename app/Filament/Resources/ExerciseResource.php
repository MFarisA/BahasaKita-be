<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExerciseResource\Pages;
use App\Filament\Resources\ExerciseResource\RelationManagers\SubmissionsRelationManager;
use App\Models\Exercise;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Str;

class ExerciseResource extends Resource
{
    protected static ?string $model = Exercise::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('sub_unit_id')
                    ->label('Unit')
                    ->relationship('subunit', 'title')
                    ->searchable()
                    ->required()
                    ->preload(),

                Select::make('type')
                    ->options([
                        'multiple_choice' => 'Multiple Choice',
                    ])
                    ->required()
                    ->label('Jenis Soal'),

                TextInput::make('xp')
                    ->label('Poin')
                    ->numeric()
                    ->required()
                    ->default(10),

                Textarea::make('content.question')
                    ->label('Pertanyaan')
                    ->required(),

                Repeater::make('content.choices')
                    ->label('Pilihan Ganda')
                    ->schema([
                        TextInput::make('text')
                            ->label('Teks Pilihan')
                            ->required(),
                        FileUpload::make('image')
                            ->label('Gambar (opsional)')
                            ->image()
                            ->disk('public')
                            ->directory('exercise-choices')
                            ->visibility('public')
                            ->maxSize(2048),
                    ])
                    ->required()
                    ->minItems(2)
                    ->columns(2),

                Select::make('answer.correct_index')
                    ->label('Jawaban Benar')
                    ->options(function (callable $get) {
                        $choices = array_values($get('content.choices') ?? []);
                        $options = [];

                        foreach ($choices as $index => $choice) {
                            $text = $choice['text'] ?? "Pilihan #" . ($index + 1);
                            $options[$index] = $text;
                        }

                        return $options;
                    })
                    ->required()
                    ->reactive()
                    ->disabled(fn(callable $get) => empty($get('content.choices')))
                    ->hint('Pilih berdasarkan teks pilihan'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('subunit.title')
                    ->label('Unit')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Tipe soal')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'multiple_choice' => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('xp')
                    ->label('Poin')
                    ->sortable()
                    ->numeric(),

                TextColumn::make('content')
                    ->label('Isi Soal')
                    ->formatStateUsing(fn($state) => Str::limit($state['question'] ?? '-', 50))
                    ->tooltip(fn($state) => $state['question'] ?? '-')
                    ->wrap(),

                TextColumn::make('answer')
                    ->label('Jawaban Benar')
                    ->formatStateUsing(function ($state, $record) {
                        $index = $state['correct_index'] ?? null;
                        return $record->content['choices'][$index]['text'] ?? '—';
                    })
                    ->wrap(),
            ])
            ->filters([])
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
            SubmissionsRelationManager::class,
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
