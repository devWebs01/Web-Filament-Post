<?php

namespace App\Filament\Resources\PostRelationManagerResource\RelationManagers;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use FilamentTiptapEditor\TiptapEditor;

class PostRelationManager extends RelationManager
{
    protected static string $relationship = 'posts';

    protected static ?string $modelLabel = 'Artikel'; // Singular (untuk satu item)

    protected static ?string $title = 'Daftar Artikel'; // Judul tabel atau section

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('thumbnail')
                    ->label('Thumbnail')
                    ->required()
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        null,
                        '16:9',
                        '4:3',
                        '1:1',
                    ])
                    ->disk('public') // ⬅️ tambahkan ini
                    ->directory('posts') // opsional, untuk rapi
                    ->columnSpan(2),
                TextInput::make('title')
                    ->label('Judul')
                    ->placeholder('Masukkan judul')
                    ->required()
                    ->string()
                    ->minLength(5)
                    ->columnSpan(2),
                Select::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('status')
                    ->options([
                        'PUBLISH' => 'Publish',
                        'DRAF' => 'Draf',
                    ])
                    ->required(),
                SpatieTagsInput::make('tags')
                    ->label('Tags')
                    ->type('categories') // opsional, default: null (jika tidak pakai tag type)
                    ->suggestions(['Laravel', 'PHP', 'Vue', 'Livewire']) // opsional
                    ->placeholder('Tambah tag...')
                    ->hint('Gunakan Enter atau koma untuk menambahkan')
                    ->required()
                    ->columnSpan(2),
                TiptapEditor::make('body')
                    ->label('Isi')
                    ->profile('simple')
                    ->maxContentWidth('5xl')
                    ->required()
                    ->columnSpan(2),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc') // ⬅️ Data terbaru di atas
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->alignCenter()
                    ->rounded()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->alignCenter()
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->alignCenter()
                    ->badge()
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->button(),
                Tables\Actions\DeleteAction::make()
                    ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
