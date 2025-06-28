<?php

namespace App\Filament\Resources\PostRelationManagerResource\RelationManagers;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategoryRelationManager extends RelationManager
{
    protected static string $relationship = 'category';

    protected static ?string $modelLabel = 'Kategori'; // Singular (untuk satu item)

    protected static ?string $title = 'Daftar Kategori'; // Judul tabel atau section

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama Kategori')
                    ->placeholder('Masukkan nama kategori')
                    ->required()
                    ->minLength(5),
                Textarea::make('desc')
                    ->label('Deskripsi')
                    ->placeholder('Masukkan deskripsi kategori')
                    ->required()
                    ->minLength(5),
            ])->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc') // ⬅️ Data terbaru di atas
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->label('Nama Kategori')
                    ->alignCenter()
                    ->limit(15)
                    ->sortable(),
                TextColumn::make('desc')
                    ->searchable()
                    ->label('Deskripsi Kategori')
                    ->alignCenter()
                    ->limit(15)
                    ->sortable(),
            ])
            ->filters([

            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
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
