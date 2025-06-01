<?php

namespace App\Filament\Resources\SubUnitResource\RelationManagers;

use App\Filament\Resources\ExerciseSubmissionResource;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ExercisesRelationManager extends RelationManager
{
    protected static string $relationship = 'exercises';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('type')
                    ->options([
                        'multiple_choice' => 'Multiple Choice',
                    ])
                    ->required()
                    ->label('Jenis Soal'),

                // FileUpload::make('gambar')
                //     ->label('Gambar Soal')
                //     ->image()
                //     ->dehydrated()
                //     ->imageEditor()
                //     ->imageCropAspectRatio('1:1')
                //     ->maxSize(2048)
                //     ->disk('public')
                //     ->directory('exercise-photos')
                //     ->visibility('public')
                //     ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml']),

                TextInput::make('xp')
                    ->label('poin')
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('type')
            ->columns([
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
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\AttachAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('kelolaSubmissions')
                    ->label('Submissions')
                    ->url(fn($record) => route('filament.admin.resources.exercises.edit', ['record' => $record]))
                    ->icon('heroicon-o-document-text'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
