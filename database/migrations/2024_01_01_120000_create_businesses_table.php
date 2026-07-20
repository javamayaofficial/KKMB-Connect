<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nama');
            $table->string('kategori')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('kontak_wa', 30)->nullable();
            $table->string('kota')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->boolean('is_featured')->default(false);
            $table->date('featured_until')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('kategori');
            $table->index(['is_featured', 'featured_until']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
