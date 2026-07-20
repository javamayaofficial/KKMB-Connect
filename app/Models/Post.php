<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $fillable = [
        'author_user_id', 'tipe', 'judul', 'slug', 'konten',
        'gambar_path', 'status', 'published_at',
    ];

    protected function casts(): array
    {
        return ['published_at' => 'datetime'];
    }

    protected static function booted(): void
    {
        static::creating(function (Post $post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->judul).'-'.Str::random(6);
            }
        });
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_user_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')->whereNotNull('published_at');
    }
}
