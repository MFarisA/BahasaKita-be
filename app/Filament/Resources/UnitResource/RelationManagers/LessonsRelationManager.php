<?php

namespace App\Filament\Resources\UnitResource\RelationManagers;

use Dom\Text;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LessonsRelationManager extends RelationManager
{
    protected static string $relationship = 'lessons';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->label('Judul Pelajaran')
                    ->maxLength(50),
                TextInput::make('content')
                    ->required()
                    ->label('Konten Pelajaran')
                    ->maxLength(500),
                TextInput::make('order')
                    ->label('Urutan Pelajaran')
                    ->numeric()
                    ->required()
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')
                    ->label('Judul pelajaran')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('content')
                    ->label('Konten pelajaran')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('order')
                    ->searchable()
                    ->sortable()
                    ->label('Urutan pelajaran'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('manageLessons')
                    ->label('Kelola latihan')
                    ->url(fn($record) => $record ? route('filament.admin.resources.lessons.edit', ['record' => $record->id]) : '#')
                    ->icon('heroicon-o-book-open'),
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
