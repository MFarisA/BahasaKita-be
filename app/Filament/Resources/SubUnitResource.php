<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubUnitResource\Pages;
use App\Filament\Resources\SubUnitResource\RelationManagers;
use App\Filament\Resources\SubUnitResource\RelationManagers\ExercisesRelationManager;
use App\Models\SubUnit;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubUnitResource extends Resource
{
    protected static ?string $model = SubUnit::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('unit_id')
                    ->label('Unit')
                    ->required()
                    ->relationship('unit', 'title'),
                TextInput::make('title')
                    ->required()
                    ->maxLength(50)
                    ->label('Judul Sub Unit'),
                TextInput::make('order')
                    ->numeric()
                    ->default(0)
                    ->required()
                    ->label('Urutan'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('unit.title')
                    ->label('Unit')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('order')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Urutan'),
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
            ExercisesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubUnits::route('/'),
            'create' => Pages\CreateSubUnit::route('/create'),
            'edit' => Pages\EditSubUnit::route('/{record}/edit'),
        ];
    }
}
