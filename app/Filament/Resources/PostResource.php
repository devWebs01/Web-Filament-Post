<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostRelationManagerResource\RelationManagers\CategoryRelationManager;
use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use FilamentTiptapEditor\TiptapEditor;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $modelLabel = 'Artikel';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
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
                    ])

                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc') // ⬅️ Data terbaru di atas
            ->columns([
                TextColumn::make('title')
                    ->alignCenter()
                    ->label('Judul')
                    ->searchable(),
                TextColumn::make('status')
                    ->alignCenter()
                    ->badge()
                    ->searchable(),
                TextColumn::make('category.name')
                    ->alignCenter()
                    ->label('Kategori')
                    ->searchable(),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'PUBLISH' => 'Publish',
                        'DRAF' => 'Draf',
                    ]),
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

    public static function getRelations(): array
    {
        return [
            CategoryRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
