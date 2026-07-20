<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('slug')->unique();
            $table->unsignedInteger('harga')->default(0);
            $table->unsignedInteger('durasi_hari')->default(365);
            $table->text('deskripsi_fitur')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained('subscription_plans')->cascadeOnDelete();
            $table->enum('status', ['pending', 'active', 'expired', 'rejected'])->default('pending');
            $table->date('mulai_at')->nullable();
            $table->date('berakhir_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // polymorphic ringan: reference_type = subscription | business_featured
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->unsignedInteger('jumlah');
            $table->enum('metode', ['manual_transfer', 'qris_manual'])->default('manual_transfer');
            $table->string('bukti_path')->nullable();
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('catatan_admin')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'reference_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('subscription_plans');
    }
};
