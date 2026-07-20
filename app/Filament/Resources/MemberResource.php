<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemberResource\Pages;
use App\Models\AuditLog;
use App\Models\User;
use App\Services\Notification\NotificationService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class MemberResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Anggota';
    protected static ?string $modelLabel = 'Anggota';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->label('Nama')->required(),
            Forms\Components\TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
            Forms\Components\TextInput::make('phone')->label('No. WhatsApp'),
            Forms\Components\Select::make('status')->options([
                'pending' => 'Menunggu Verifikasi',
                'active' => 'Aktif',
                'rejected' => 'Ditolak',
                'suspended' => 'Ditangguhkan',
            ])->required(),
            Forms\Components\Select::make('roles')->relationship('roles', 'name')
                ->multiple()->preload()->label('Peran'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('profile.angkatan')->label('Angkatan'),
                Tables\Columns\TextColumn::make('profile.kota')->label('Kota'),
                Tables\Columns\TextColumn::make('status')->badge()->colors([
                    'warning' => 'pending', 'success' => 'active',
                    'danger' => 'rejected', 'gray' => 'suspended',
                ]),
                Tables\Columns\TextColumn::make('created_at')->label('Daftar')->date()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'pending' => 'Menunggu Verifikasi', 'active' => 'Aktif',
                    'rejected' => 'Ditolak', 'suspended' => 'Ditangguhkan',
                ]),
            ])
            ->actions([
                // APPROVAL WORKFLOW: verifikasi anggota + kirim notifikasi + audit log
                Tables\Actions\Action::make('verifikasi')
                    ->icon('heroicon-o-check-badge')->color('success')
                    ->visible(fn (User $r) => $r->status === 'pending')
                    ->requiresConfirmation()
                    ->action(function (User $record, NotificationService $notif) {
                        $record->update(['status' => 'active']);
                        $record->syncRoles(['alumni']);
                        if ($record->profile) {
                            $record->profile->update([
                                'verified_at' => now(),
                                'verified_by' => auth()->id(),
                                'member_number' => $record->profile->member_number
                                    ?: 'KKMB-'.str_pad((string) $record->id, 4, '0', STR_PAD_LEFT),
                                'qr_token' => $record->profile->qr_token ?: Str::random(40),
                            ]);
                        }
                        AuditLog::record('verifikasi_anggota', 'User', $record->id);
                        $notif->notify($record, 'Akun Terverifikasi',
                            'Selamat! Akun Anda telah diverifikasi. Kartu keanggotaan digital sudah aktif.',
                            ['in_app', 'wa', 'email']);
                    }),
                Tables\Actions\Action::make('tolak')
                    ->icon('heroicon-o-x-circle')->color('danger')
                    ->visible(fn (User $r) => $r->status === 'pending')
                    ->requiresConfirmation()
                    ->action(function (User $record, NotificationService $notif) {
                        $record->update(['status' => 'rejected']);
                        AuditLog::record('tolak_anggota', 'User', $record->id);
                        $notif->notify($record, 'Pendaftaran Ditolak',
                            'Mohon maaf, pendaftaran Anda belum dapat kami setujui. Silakan hubungi pengurus.',
                            ['in_app', 'email']);
                    }),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMembers::route('/'),
        ];
    }
}
