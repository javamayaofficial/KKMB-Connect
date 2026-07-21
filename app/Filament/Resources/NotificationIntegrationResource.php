<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationIntegrationResource\Pages;
use App\Models\AuditLog;
use App\Models\NotificationIntegration;
use App\Services\Notification\NotificationService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NotificationIntegrationResource extends Resource
{
    protected static ?string $model = NotificationIntegration::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Integrasi Notifikasi';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $modelLabel = 'Integrasi Notifikasi';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Nama Integrasi')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('channel')
                ->label('Channel')
                ->disabled(),
            Forms\Components\Select::make('provider')
                ->label('Provider')
                ->options(fn (?NotificationIntegration $record) => $record?->channel === 'email'
                    ? ['mailketing' => 'Mailketing']
                    : ['fonnte' => 'Fonnte'])
                ->required(),
            Forms\Components\TextInput::make('config.api_key')
                ->label(fn (?NotificationIntegration $record) => $record?->channel === 'email' ? 'Email API Key' : 'WhatsApp API Key')
                ->password()
                ->revealable()
                ->required(),
            Forms\Components\Toggle::make('is_active')
                ->label('Status Aktif')
                ->inline(false),
            Forms\Components\Section::make('WhatsApp')
                ->schema([
                    Forms\Components\TextInput::make('config.device_id')
                        ->label('Device ID')
                        ->helperText('Opsional, isi jika device tertentu ingin dipakai eksplisit.'),
                ])
                ->visible(fn (?NotificationIntegration $record) => $record?->channel === 'whatsapp'),
            Forms\Components\Section::make('Email')
                ->schema([
                    Forms\Components\TextInput::make('config.sender_name')
                        ->label('Sender Name')
                        ->required(fn (?NotificationIntegration $record) => $record?->channel === 'email'),
                    Forms\Components\TextInput::make('config.sender_email')
                        ->label('Sender Email')
                        ->email()
                        ->required(fn (?NotificationIntegration $record) => $record?->channel === 'email'),
                ])
                ->visible(fn (?NotificationIntegration $record) => $record?->channel === 'email'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Integrasi')->searchable(),
                Tables\Columns\TextColumn::make('channel')->label('Channel')->badge(),
                Tables\Columns\TextColumn::make('provider')->label('Provider')->badge(),
                Tables\Columns\IconColumn::make('is_active')->label('Aktif')->boolean(),
                Tables\Columns\TextColumn::make('last_test_status')
                    ->label('Status Test')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'sent',
                        'danger' => 'failed',
                    ]),
                Tables\Columns\TextColumn::make('last_tested_at')->label('Terakhir Dites')->since(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('test')
                    ->label(fn (NotificationIntegration $record) => $record->channel === 'email' ? 'Tes Email' : 'Tes Koneksi')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('primary')
                    ->form(fn (NotificationIntegration $record) => $record->channel === 'email'
                        ? [
                            Forms\Components\TextInput::make('recipient')->label('Email Tujuan')->email()->required(),
                            Forms\Components\TextInput::make('subject')->label('Subjek')->default('Tes Email KKMB Connect')->required(),
                            Forms\Components\Textarea::make('message')
                                ->label('Isi Email (HTML)')
                                ->rows(8)
                                ->default('<p>Ini adalah email uji dari integrasi KKMB Connect.</p>')
                                ->helperText('Gunakan HTML sederhana untuk menguji template email.')
                                ->required(),
                        ]
                        : [
                            Forms\Components\TextInput::make('recipient')->label('Nomor WhatsApp')->required(),
                            Forms\Components\Textarea::make('message')->label('Isi Pesan')->default('Ini adalah pesan uji dari integrasi KKMB Connect.')->required(),
                        ])
                    ->action(function (NotificationIntegration $record, array $data, NotificationService $notifications) {
                        $record->update([
                            'last_test_status' => 'pending',
                            'last_test_message' => 'Tes notifikasi dimasukkan ke queue.',
                            'last_tested_at' => now(),
                        ]);

                        if ($record->channel === 'email') {
                            $notifications->sendDirectEmail(
                                $data['recipient'],
                                $data['subject'],
                                $data['message'],
                                ['delivery_type' => 'integration_test'],
                                auth()->user(),
                            );
                        } else {
                            $notifications->sendDirectWhatsApp(
                                $data['recipient'],
                                $data['message'],
                                ['delivery_type' => 'integration_test', 'subject' => 'Tes WhatsApp KKMB Connect'],
                                auth()->user(),
                            );
                        }

                        AuditLog::record('test_integrasi_notifikasi', 'NotificationIntegration', $record->id, [
                            'channel' => $record->channel,
                            'provider' => $record->provider,
                            'recipient' => $data['recipient'],
                        ]);
                    }),
            ])
            ->paginated(false);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotificationIntegrations::route('/'),
            'edit' => Pages\EditNotificationIntegration::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
