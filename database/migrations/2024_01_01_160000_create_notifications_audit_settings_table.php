<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Notifikasi in-app (channel wa/email diproses terpisah oleh NotificationService)
        Schema::create('app_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('tipe')->default('info');
            $table->string('judul');
            $table->text('pesan');
            $table->string('url')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'read_at']);
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('aksi');
            $table->string('entitas')->nullable();
            $table->unsignedBigInteger('entitas_id')->nullable();
            $table->json('meta')->nullable();
            $table->string('ip', 45)->nullable();
            $table->timestamps();

            $table->index(['entitas', 'entitas_id']);
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('app_notifications');
    }
};
