<?php

namespace App\Filament\Resources\UnitResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
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

                FileUpload::make('gambar')
                    ->label('Gambar Soal')
                    ->image()
                    ->dehydrated()
                    ->imageEditor()
                    ->imageCropAspectRatio('1:1')
                    ->maxSize(2048)
                    ->disk('public')
                    ->directory('exercise-photos')
                    ->visibility('public')
                    ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml']),

                TextInput::make('xp')
                    ->label('poin')
                    ->numeric()
                    ->required()
                    ->default(10),

                KeyValue::make('content')
                    ->label('Isi Soal')
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('type')
                    ->label('Tipe soal')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'multiple_choice' => 'success',
                        default => 'gray',
                    }),

                ImageColumn::make('gambar')
                    ->label('Gambar')
                    ->size(50),

                TextColumn::make('xp')
                    ->label('Poin')
                    ->sortable()
                    ->numeric(),

                TextColumn::make('content')
                    ->label('Isi Soal')
                    ->formatStateUsing(fn($state) => Str::limit(json_encode($state), 50))
                    ->tooltip(fn($state) => json_encode($state))
                    ->wrap(),

                TextColumn::make('answer')
                    ->label('Jawaban benar')
                    ->formatStateUsing(fn($state) => Str::limit(json_encode($state), 50))
                    ->tooltip(fn($state) => json_encode($state))
                    ->wrap(),
            ])
            ->filters([
                //
            ])
            ->headerActions([

                Tables\Actions\CreateAction::make(),
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
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
