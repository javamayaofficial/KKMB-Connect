<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use App\Services\Payment\PaymentService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Pembayaran';
    protected static ?string $modelLabel = 'Pembayaran';
    protected static ?int $navigationSort = 4;

    public static function getNavigationBadge(): ?string
    {
        return (string) Payment::pending()->count() ?: null;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Textarea::make('catatan_admin')->label('Catatan')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Anggota')->searchable(),
                Tables\Columns\TextColumn::make('jumlah')->money('IDR')->sortable(),
                Tables\Columns\TextColumn::make('metode')->badge(),
                Tables\Columns\ImageColumn::make('bukti_path')->label('Bukti')->disk('public'),
                Tables\Columns\TextColumn::make('status')->badge()->colors([
                    'warning' => 'pending', 'success' => 'verified', 'danger' => 'rejected',
                ]),
                Tables\Columns\TextColumn::make('created_at')->date()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'pending' => 'Pending', 'verified' => 'Terverifikasi', 'rejected' => 'Ditolak',
                ]),
            ])
            ->actions([
                Tables\Actions\Action::make('verifikasi')
                    ->icon('heroicon-o-check')->color('success')
                    ->visible(fn (Payment $p) => $p->status === 'pending' && $p->bukti_path)
                    ->requiresConfirmation()
                    ->action(fn (Payment $record, PaymentService $svc) => $svc->verify($record, auth()->user())),
                Tables\Actions\Action::make('tolak')
                    ->icon('heroicon-o-x-mark')->color('danger')
                    ->visible(fn (Payment $p) => $p->status === 'pending')
                    ->form([Forms\Components\Textarea::make('catatan')->label('Alasan')])
                    ->action(fn (Payment $record, array $data, PaymentService $svc) => $svc->reject($record, auth()->user(), $data['catatan'] ?? null)),
            ]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListPayments::route('/')];
    }
}
