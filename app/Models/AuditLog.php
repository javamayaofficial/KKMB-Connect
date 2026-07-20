<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = ['user_id', 'aksi', 'entitas', 'entitas_id', 'meta', 'ip'];

    protected function casts(): array
    {
        return ['meta' => 'array'];
    }

    public static function record(string $aksi, ?string $entitas = null, ?int $entitasId = null, array $meta = []): void
    {
        static::create([
            'user_id' => auth()->id(),
            'aksi' => $aksi,
            'entitas' => $entitas,
            'entitas_id' => $entitasId,
            'meta' => $meta,
            'ip' => request()->ip(),
        ]);
    }
}
