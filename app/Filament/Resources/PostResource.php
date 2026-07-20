<?php

namespace App\Filament\Resources;

use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;
    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $navigationLabel = 'Feed & Artikel';
    protected static ?string $modelLabel = 'Artikel';
    protected static ?int $navigationSort = 5;

    public static function getNavigationBadge(): ?string
    {
        return (string) Post::where('status', 'pending')->count() ?: null;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('judul')->required()->columnSpanFull(),
            Forms\Components\Select::make('tipe')->options(['artikel' => 'Artikel', 'pengumuman' => 'Pengumuman'])->default('artikel'),
            Forms\Components\FileUpload::make('gambar_path')->image()->disk('public')->directory('posts')->maxSize(2048),
            Forms\Components\RichEditor::make('konten')->required()->columnSpanFull(),
            Forms\Components\Select::make('status')->options([
                'draft' => 'Draft', 'pending' => 'Menunggu Moderasi', 'published' => 'Publish',
            ])->default('draft')->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul')->searchable()->limit(40),
                Tables\Columns\TextColumn::make('author.name')->label('Penulis'),
                Tables\Columns\TextColumn::make('status')->badge()->colors([
                    'gray' => 'draft', 'warning' => 'pending', 'success' => 'published',
                ]),
                Tables\Columns\TextColumn::make('created_at')->date(),
            ])
            ->actions([
                Tables\Actions\Action::make('publish')->icon('heroicon-o-check')->color('success')
                    ->visible(fn (Post $p) => $p->status !== 'published')
                    ->action(fn (Post $record) => $record->update(['status' => 'published', 'published_at' => now()])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
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
