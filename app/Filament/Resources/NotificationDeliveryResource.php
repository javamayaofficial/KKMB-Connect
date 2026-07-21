<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationDeliveryResource\Pages;
use App\Models\NotificationDelivery;
use App\Services\Notification\NotificationEventRegistry;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NotificationDeliveryResource extends Resource
{
    protected static ?string $model = NotificationDelivery::class;

    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';

    protected static ?string $navigationLabel = 'Log Notifikasi';

    protected static ?string $navigationGroup = 'Notifikasi';

    protected static ?string $modelLabel = 'Log Notifikasi';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->label('Waktu')->since()->sortable(),
                Tables\Columns\TextColumn::make('delivery_type')->label('Tipe')->badge(),
                Tables\Columns\TextColumn::make('event_key')
                    ->label('Event')
                    ->formatStateUsing(fn (?string $state) => $state ? (NotificationEventRegistry::labels()[$state] ?? $state) : 'Manual')
                    ->searchable(),
                Tables\Columns\TextColumn::make('channel')->badge(),
                Tables\Columns\TextColumn::make('recipient')->label('Tujuan')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('subject')->label('Subject')->limit(40),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'sent',
                        'danger' => 'failed',
                    ]),
                Tables\Columns\TextColumn::make('attempts')->label('Retry'),
                Tables\Columns\TextColumn::make('last_error')->label('Error')->limit(50)->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('channel')->options([
                    'whatsapp' => 'WhatsApp',
                    'email' => 'Email',
                ]),
                Tables\Filters\SelectFilter::make('status')->options([
                    'pending' => 'Pending',
                    'sent' => 'Terkirim',
                    'failed' => 'Gagal',
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotificationDeliveries::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
