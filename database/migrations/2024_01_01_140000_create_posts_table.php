<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('tipe', ['artikel', 'pengumuman'])->default('artikel');
            $table->string('judul');
            $table->string('slug')->unique();
            $table->longText('konten');
            $table->string('gambar_path')->nullable();
            // draft -> pending (butuh moderasi utk anggota) -> published
            $table->enum('status', ['draft', 'pending', 'published'])->default('pending');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
