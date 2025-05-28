<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UnitsRelationManager extends RelationManager
{
    protected static string $relationship = 'units';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Judul level')
                    ->required()
                    ->maxLength(255),
                TextInput::make('order')
                    ->label('Urutan')
                    ->numeric()
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')
                    ->label('Judul Unit')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('order')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                

                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('manageLessons')
                    ->label('Kelola Materi')
                    ->url(fn($record) => $record ? route('filament.admin.resources.units.edit', ['record' => $record->id]) : '#')
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
