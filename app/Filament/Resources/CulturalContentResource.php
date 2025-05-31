<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CulturalContentResource\Pages;
use App\Models\CulturalContent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Get;
use Filament\Tables\Filters\SelectFilter;

class CulturalContentResource extends Resource
{
    protected static ?string $model = CulturalContent::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Dasar')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('type')
                                    ->label('Tipe Konten')
                                    ->options([
                                        'story' => 'Cerita',
                                        'proverb' => 'Peribahasa',
                                        'trivia' => 'Fakta Menarik',
                                    ])
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, $set) {
                                        // Reset fields when type changes
                                        if ($state !== 'story') {
                                            $set('title', null);
                                            $set('excerpt', null);
                                            $set('image_url', null);
                                            $set('full_content', null);
                                        }
                                        if ($state !== 'proverb') {
                                            $set('text', null);
                                            $set('translation', null);
                                            $set('explanation', null);
                                        }
                                        if ($state !== 'trivia') {
                                            $set('category', null);
                                            $set('fact', null);
                                        }
                                    }),
                                
                                Forms\Components\TextInput::make('language')
                                    ->label('Bahasa')
                                    ->default('BahasaKita')
                                    ->required(),
                            ]),
                        
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Aktif')
                                    ->default(true),
                                
                                Forms\Components\TextInput::make('sort_order')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->default(0),
                            ]),
                    ]),

                // Section for Stories
                Section::make('Konten Cerita')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul Cerita')
                            ->maxLength(255)
                            ->required(fn (Get $get) => $get('type') === 'story'),
                        
                        Forms\Components\Textarea::make('excerpt')
                            ->label('Ringkasan Cerita')
                            ->rows(3)
                            ->required(fn (Get $get) => $get('type') === 'story'),
                        
                        Forms\Components\TextInput::make('image_url')
                            ->label('URL Gambar')
                            ->url()
                            ->placeholder('https://example.com/image.jpg'),
                        
                        Forms\Components\RichEditor::make('full_content')
                            ->label('Cerita Lengkap')
                            ->columnSpanFull(),
                    ])
                    ->visible(fn (Get $get) => $get('type') === 'story'),

                // Section for Proverbs
                Section::make('Konten Peribahasa')
                    ->schema([
                        Forms\Components\Textarea::make('text')
                            ->label('Teks Peribahasa')
                            ->rows(2)
                            ->required(fn (Get $get) => $get('type') === 'proverb'),
                        
                        Forms\Components\Textarea::make('translation')
                            ->label('Terjemahan')
                            ->rows(2)
                            ->required(fn (Get $get) => $get('type') === 'proverb'),
                        
                        Forms\Components\Textarea::make('explanation')
                            ->label('Penjelasan')
                            ->rows(4)
                            ->required(fn (Get $get) => $get('type') === 'proverb'),
                    ])
                    ->visible(fn (Get $get) => $get('type') === 'proverb'),

                // Section for Trivia
                Section::make('Konten Fakta Menarik')
                    ->schema([
                        Forms\Components\TextInput::make('category')
                            ->label('Kategori')
                            ->maxLength(255)
                            ->required(fn (Get $get) => $get('type') === 'trivia'),
                        
                        Forms\Components\Textarea::make('fact')
                            ->label('Fakta')
                            ->rows(3)
                            ->required(fn (Get $get) => $get('type') === 'trivia'),
                    ])
                    ->visible(fn (Get $get) => $get('type') === 'trivia'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'story' => 'info',
                        'proverb' => 'warning',
                        'trivia' => 'success',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'story' => 'Cerita',
                        'proverb' => 'Peribahasa',
                        'trivia' => 'Fakta',
                        default => $state,
                    }),
                
                Tables\Columns\TextColumn::make('language')
                    ->label('Bahasa')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul/Teks')
                    ->searchable()
                    ->limit(50)
                    ->formatStateUsing(function ($record) {
                        return match ($record->type) {
                            'story' => $record->title,
                            'proverb' => $record->text,
                            'trivia' => $record->fact,
                            default => '-',
                        };
                    }),
                
                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->searchable()
                    ->placeholder('-')
                    ->toggleable(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean(),
                
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Tipe Konten')
                    ->options([
                        'story' => 'Cerita',
                        'proverb' => 'Peribahasa',
                        'trivia' => 'Fakta Menarik',
                    ]),
                
                SelectFilter::make('language')
                    ->label('Bahasa')
                    ->options([
                        'BahasaKita' => 'BahasaKita',
                        'Bahasa Indonesia' => 'Bahasa Indonesia',
                        'English' => 'English',
                    ]),
                
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order', 'asc');
            
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCulturalContents::route('/'),
            'create' => Pages\CreateCulturalContent::route('/create'),
            'edit' => Pages\EditCulturalContent::route('/{record}/edit'),
        ];
    }
}