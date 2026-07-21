<?php

namespace App\Filament\Resources\NotificationIntegrationResource\Pages;

use App\Filament\Resources\NotificationIntegrationResource;
use App\Models\AuditLog;
use Filament\Resources\Pages\EditRecord;

class EditNotificationIntegration extends EditRecord
{
    protected static string $resource = NotificationIntegrationResource::class;

    protected function afterSave(): void
    {
        AuditLog::record('update_integrasi_notifikasi', 'NotificationIntegration', $this->record->id, [
            'channel' => $this->record->channel,
            'provider' => $this->record->provider,
            'is_active' => $this->record->is_active,
        ]);
    }
}
