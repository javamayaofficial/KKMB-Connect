<?php

namespace App\Filament\Resources;

use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Event';
    protected static ?string $modelLabel = 'Event';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('judul')->required()->columnSpanFull(),
            Forms\Components\Textarea::make('deskripsi')->columnSpanFull(),
            Forms\Components\FileUpload::make('poster_path')->label('Poster')->image()
                ->disk('public')->directory('posters')->maxSize(2048),
            Forms\Components\DateTimePicker::make('mulai_at')->label('Mulai')->required(),
            Forms\Components\DateTimePicker::make('selesai_at')->label('Selesai'),
            Forms\Components\TextInput::make('lokasi'),
            Forms\Components\TextInput::make('kuota')->numeric()->helperText('Kosongkan untuk tanpa batas'),
            Forms\Components\Toggle::make('is_paid')->label('Berbayar')->live(),
            Forms\Components\TextInput::make('harga')->numeric()->default(0)
                ->visible(fn (Forms\Get $get) => $get('is_paid')),
            Forms\Components\Select::make('status')->options([
                'draft' => 'Draft', 'published' => 'Publish', 'closed' => 'Tutup',
            ])->default('draft')->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('mulai_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('registrations_count')->counts('registrations')->label('Pendaftar'),
                Tables\Columns\TextColumn::make('status')->badge()->colors([
                    'gray' => 'draft', 'success' => 'published', 'danger' => 'closed',
                ]),
            ])
            ->defaultSort('mulai_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();
        return $data;
    }
}
