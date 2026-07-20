<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('member_number')->nullable()->unique();
            $table->string('angkatan', 10)->nullable();
            $table->string('profesi')->nullable();
            $table->string('bidang_usaha')->nullable();
            $table->string('kota')->nullable();
            $table->string('negara')->default('Indonesia');
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->text('bio')->nullable();
            $table->string('foto_path')->nullable();
            $table->string('qr_token', 64)->unique();
            $table->boolean('is_visible')->default(true);
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['profesi', 'bidang_usaha']);
            $table->index(['kota', 'negara']);
            $table->index('angkatan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_profiles');
    }
};
