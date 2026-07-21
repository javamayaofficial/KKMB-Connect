<?php
namespace App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource;
use App\Services\Notification\NotificationService;
use Filament\Resources\Pages\CreateRecord;
class CreateEvent extends CreateRecord {
    protected static string $resource = EventResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array {
        $data['created_by'] = auth()->id();
        return $data;
    }

    protected function afterCreate(): void
    {
        if ($this->record->status !== 'published' || $this->record->published_notified_at) {
            return;
        }

        app(NotificationService::class)->broadcastUsers(
            app(NotificationService::class)->recipientsForScope('active_members'),
            'new_event',
            [
                'event_title' => $this->record->judul,
                'event_date' => $this->record->mulai_at?->translatedFormat('d M Y H:i'),
                'event_location' => $this->record->lokasi ?: 'Lokasi akan diinformasikan',
                'event_url' => route('events.show', $this->record),
                'url' => route('events.show', $this->record),
            ],
            ['in_app', 'wa', 'email'],
            auth()->user(),
        );

        $this->record->forceFill(['published_notified_at' => now()])->save();
    }
}
