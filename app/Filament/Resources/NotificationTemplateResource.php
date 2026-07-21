<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationTemplateResource\Pages;
use App\Models\NotificationTemplate;
use App\Services\Notification\NotificationEventRegistry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NotificationTemplateResource extends Resource
{
    protected static ?string $model = NotificationTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Template Notifikasi';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $modelLabel = 'Template Notifikasi';

    protected static ?int $navigationSort = 11;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('event_key')
                ->label('Event')
                ->options(NotificationEventRegistry::labels())
                ->searchable()
                ->required(),
            Forms\Components\Select::make('channel')
                ->label('Channel')
                ->options(NotificationEventRegistry::channels())
                ->required(),
            Forms\Components\TextInput::make('name')
                ->label('Nama Template')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('subject')
                ->label('Subject / Judul')
                ->helperText('Untuk WhatsApp boleh dikosongkan.'),
            Forms\Components\Textarea::make('content')
                ->label('Konten Template')
                ->rows(14)
                ->helperText('Email mendukung HTML. Gunakan placeholder seperti {{name}}, {{url}}, {{message}}, {{event_title}}.')
                ->required(),
            Forms\Components\Toggle::make('is_active')
                ->label('Aktif')
                ->inline(false)
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama')->searchable(),
                Tables\Columns\TextColumn::make('event_key')
                    ->label('Event')
                    ->formatStateUsing(fn (string $state) => NotificationEventRegistry::labels()[$state] ?? $state)
                    ->searchable(),
                Tables\Columns\TextColumn::make('channel')->badge(),
                Tables\Columns\IconColumn::make('is_active')->label('Aktif')->boolean(),
                Tables\Columns\TextColumn::make('updated_at')->label('Diperbarui')->since(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotificationTemplates::route('/'),
            'create' => Pages\CreateNotificationTemplate::route('/create'),
            'edit' => Pages\EditNotificationTemplate::route('/{record}/edit'),
        ];
    }
}
