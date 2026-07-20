<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('poster_path')->nullable();
            $table->dateTime('mulai_at');
            $table->dateTime('selesai_at')->nullable();
            $table->string('lokasi')->nullable();
            $table->unsignedInteger('kuota')->nullable(); // null = tanpa batas
            $table->boolean('is_paid')->default(false);
            $table->unsignedInteger('harga')->default(0);
            $table->enum('status', ['draft', 'published', 'closed'])->default('draft');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->index(['status', 'mulai_at']);
        });

        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('qr_token', 64)->unique();
            $table->enum('status', ['registered', 'checked_in', 'cancelled'])->default('registered');
            $table->timestamp('checked_in_at')->nullable();
            $table->foreignId('checked_in_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['event_id', 'user_id']); // 1 user 1 registrasi per event
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_registrations');
        Schema::dropIfExists('events');
    }
};
