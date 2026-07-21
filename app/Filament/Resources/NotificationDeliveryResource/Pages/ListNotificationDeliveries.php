<?php

namespace App\Filament\Resources\NotificationDeliveryResource\Pages;

use App\Filament\Resources\NotificationDeliveryResource;
use App\Models\AuditLog;
use App\Services\Notification\NotificationEventRegistry;
use App\Services\Notification\NotificationService;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Resources\Pages\ListRecords;

class ListNotificationDeliveries extends ListRecords
{
    protected static string $resource = NotificationDeliveryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('sendSingle')
                ->label('Kirim Tunggal')
                ->icon('heroicon-o-paper-airplane')
                ->form([
                    Forms\Components\Select::make('channel')
                        ->label('Channel')
                        ->options([
                            'whatsapp' => 'WhatsApp',
                            'email' => 'Email',
                        ])
                        ->required()
                        ->live(),
                    Forms\Components\TextInput::make('recipient')
                        ->label(fn (Get $get) => $get('channel') === 'email' ? 'Email Tujuan' : 'Nomor WhatsApp')
                        ->required(),
                    Forms\Components\TextInput::make('subject')
                        ->label('Subject')
                        ->visible(fn (Get $get) => $get('channel') === 'email')
                        ->required(fn (Get $get) => $get('channel') === 'email'),
                    Forms\Components\Textarea::make('message')
                        ->label('Pesan / HTML')
                        ->rows(8)
                        ->helperText('Untuk email, Anda dapat menulis HTML sederhana.')
                        ->required(),
                    Forms\Components\FileUpload::make('attachments')
                        ->label('Attachment Email')
                        ->multiple()
                        ->disk('public')
                        ->directory('notifications/attachments')
                        ->visible(fn (Get $get) => $get('channel') === 'email'),
                ])
                ->action(function (array $data, NotificationService $notifications) {
                    if ($data['channel'] === 'email') {
                        $notifications->sendDirectEmail(
                            $data['recipient'],
                            $data['subject'],
                            $data['message'],
                            [
                                'delivery_type' => 'manual',
                                'attachments' => collect($data['attachments'] ?? [])->map(fn (string $path) => [
                                    'path' => $path,
                                    'disk' => 'public',
                                ])->all(),
                            ],
                            auth()->user(),
                        );
                    } else {
                        $notifications->sendDirectWhatsApp(
                            $data['recipient'],
                            $data['message'],
                            ['delivery_type' => 'manual', 'subject' => 'Broadcast Manual'],
                            auth()->user(),
                        );
                    }

                    AuditLog::record('kirim_notifikasi_tunggal', 'NotificationDelivery', null, [
                        'channel' => $data['channel'],
                        'recipient' => $data['recipient'],
                    ]);
                }),
            Action::make('broadcast')
                ->label('Broadcast Admin')
                ->icon('heroicon-o-megaphone')
                ->color('success')
                ->form([
                    Forms\Components\Select::make('event_key')
                        ->label('Jenis Broadcast')
                        ->options([
                            'admin_broadcast' => NotificationEventRegistry::labels()['admin_broadcast'],
                            'cooperative_announcement' => NotificationEventRegistry::labels()['cooperative_announcement'],
                        ])
                        ->required(),
                    Forms\Components\Select::make('scope')
                        ->label('Target')
                        ->options([
                            'active_members' => 'Semua Anggota Aktif',
                            'all_users' => 'Semua User',
                            'pengurus' => 'Pengurus',
                        ])
                        ->required()
                        ->default('active_members'),
                    Forms\Components\CheckboxList::make('channels')
                        ->label('Channel')
                        ->options(NotificationEventRegistry::channels())
                        ->columns(3)
                        ->default(['in_app', 'whatsapp', 'email'])
                        ->required(),
                    Forms\Components\TextInput::make('subject')
                        ->label('Subject / Judul')
                        ->required(),
                    Forms\Components\Textarea::make('message')
                        ->label('Pesan')
                        ->rows(8)
                        ->required(),
                    Forms\Components\TextInput::make('url')
                        ->label('URL Tautan')
                        ->url()
                        ->nullable(),
                    Forms\Components\FileUpload::make('attachments')
                        ->label('Attachment Email')
                        ->multiple()
                        ->disk('public')
                        ->directory('notifications/attachments')
                        ->visible(fn (Get $get) => in_array('email', $get('channels') ?? [], true)),
                ])
                ->action(function (array $data, NotificationService $notifications) {
                    $targets = $notifications->recipientsForScope($data['scope']);

                    $count = $notifications->broadcastUsers(
                        $targets,
                        $data['event_key'],
                        [
                            'subject' => $data['subject'],
                            'message' => $data['message'],
                            'url' => $data['url'] ?? url('/'),
                        ],
                        $data['channels'],
                        auth()->user(),
                        [
                            'attachments' => collect($data['attachments'] ?? [])->map(fn (string $path) => [
                                'path' => $path,
                                'disk' => 'public',
                            ])->all(),
                        ],
                    );

                    AuditLog::record('broadcast_notifikasi', 'NotificationDelivery', null, [
                        'event_key' => $data['event_key'],
                        'scope' => $data['scope'],
                        'channels' => $data['channels'],
                        'count' => $count,
                    ]);
                }),
        ];
    }
}
