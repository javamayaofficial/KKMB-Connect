<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BusinessResource\Pages;
use App\Models\Business;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BusinessResource extends Resource
{
    protected static ?string $model = Business::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';
    protected static ?string $navigationLabel = 'Bisnis Alumni';
    protected static ?string $modelLabel = 'Bisnis';
    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return (string) Business::where('status', 'pending')->count() ?: null;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nama')->required(),
            Forms\Components\TextInput::make('kategori'),
            Forms\Components\TextInput::make('kota'),
            Forms\Components\Textarea::make('deskripsi')->columnSpanFull(),
            Forms\Components\Select::make('status')->options([
                'pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak',
            ])->required(),
            Forms\Components\Toggle::make('is_featured')->label('Featured'),
            Forms\Components\DatePicker::make('featured_until')->label('Featured s.d.'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('owner.name')->label('Pemilik'),
                Tables\Columns\TextColumn::make('kategori'),
                Tables\Columns\IconColumn::make('is_featured')->boolean(),
                Tables\Columns\TextColumn::make('status')->badge()->colors([
                    'warning' => 'pending', 'success' => 'approved', 'danger' => 'rejected',
                ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak',
                ]),
            ])
            ->actions([
                Tables\Actions\Action::make('setujui')->icon('heroicon-o-check')->color('success')
                    ->visible(fn (Business $b) => $b->status !== 'approved')
                    ->action(fn (Business $record) => $record->update(['status' => 'approved'])),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListBusinesses::route('/')];
    }
}
