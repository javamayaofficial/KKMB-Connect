<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_integrations', function (Blueprint $table) {
            $table->id();
            $table->string('channel');
            $table->string('name');
            $table->string('provider');
            $table->text('config')->nullable();
            $table->boolean('is_active')->default(false);
            $table->string('last_test_status')->nullable();
            $table->text('last_test_message')->nullable();
            $table->timestamp('last_tested_at')->nullable();
            $table->timestamps();

            $table->unique('channel');
        });

        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();
            $table->string('event_key');
            $table->string('channel');
            $table->string('name');
            $table->string('subject')->nullable();
            $table->longText('content');
            $table->boolean('is_active')->default(true);
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->unique(['event_key', 'channel']);
            $table->index(['channel', 'is_active']);
        });

        Schema::create('notification_deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('integration_id')->nullable()->constrained('notification_integrations')->nullOnDelete();
            $table->foreignId('template_id')->nullable()->constrained('notification_templates')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('event_key')->nullable();
            $table->string('delivery_type')->default('event');
            $table->string('channel');
            $table->string('provider')->nullable();
            $table->string('recipient');
            $table->string('subject')->nullable();
            $table->longText('content');
            $table->json('attachments')->nullable();
            $table->json('payload')->nullable();
            $table->json('response')->nullable();
            $table->string('status')->default('pending');
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->unsignedTinyInteger('max_attempts')->default(3);
            $table->text('last_error')->nullable();
            $table->string('provider_message_id')->nullable();
            $table->timestamp('queued_at')->nullable();
            $table->timestamp('last_attempt_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index(['channel', 'status']);
            $table->index(['event_key', 'delivery_type']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('first_login_at')->nullable()->after('email_verified_at');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->timestamp('published_notified_at')->nullable()->after('status');
        });

        Schema::table('event_registrations', function (Blueprint $table) {
            $table->timestamp('reminder_sent_at')->nullable()->after('checked_in_at');
        });

        DB::table('notification_integrations')->insert([
            [
                'channel' => 'whatsapp',
                'name' => 'Integrasi WhatsApp',
                'provider' => 'fonnte',
                'config' => null,
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'channel' => 'email',
                'name' => 'Integrasi Email',
                'provider' => 'mailketing',
                'config' => null,
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('notification_templates')->insert($this->defaultTemplates());
    }

    public function down(): void
    {
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->dropColumn('reminder_sent_at');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('published_notified_at');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('first_login_at');
        });

        Schema::dropIfExists('notification_deliveries');
        Schema::dropIfExists('notification_templates');
        Schema::dropIfExists('notification_integrations');
    }

    protected function defaultTemplates(): array
    {
        $templates = [
            'account_registration' => [
                'in_app' => ['subject' => 'Pendaftaran berhasil diterima', 'content' => 'Akun Anda berhasil dibuat. Lengkapi profil dan portofolio agar pengurus dapat meninjau profil Anda secara utuh.'],
                'whatsapp' => ['subject' => null, 'content' => "*Pendaftaran Berhasil*\n\nHalo {{name}}, akun KKMB Connect Anda sudah dibuat. Lengkapi profil dan portofolio Anda di {{app_url}} agar proses verifikasi berjalan lebih cepat."],
                'email' => ['subject' => 'Pendaftaran KKMB Connect berhasil', 'content' => '<p>Halo {{name}},</p><p>Akun KKMB Connect Anda sudah berhasil dibuat.</p><p>Silakan lengkapi profil dan portofolio Anda agar pengurus dapat meninjau profil Anda secara utuh.</p><p><a href="{{app_url}}" style="background:#0E7C86;color:#fff;padding:12px 18px;border-radius:12px;text-decoration:none;">Lengkapi Profil</a></p>'],
            ],
            'first_login' => [
                'in_app' => ['subject' => 'Login pertama berhasil', 'content' => 'Selamat datang di KKMB Connect. Jelajahi dashboard dan pastikan profil Anda selalu terbarui.'],
                'whatsapp' => ['subject' => null, 'content' => "*Selamat Datang di KKMB Connect*\n\nHalo {{name}}, login pertama Anda berhasil. Pastikan profil, portofolio, dan data keanggotaan Anda selalu terbarui."],
                'email' => ['subject' => 'Selamat datang di KKMB Connect', 'content' => '<p>Halo {{name}},</p><p>Login pertama Anda berhasil. Selamat datang di ekosistem digital KKMB Connect.</p>'],
            ],
            'forgot_password' => [
                'in_app' => ['subject' => 'Permintaan reset kata sandi', 'content' => 'Kami menerima permintaan reset kata sandi untuk akun Anda. Jika ini memang Anda, lanjutkan melalui tautan yang kami kirim.'],
                'whatsapp' => ['subject' => null, 'content' => "*Reset Kata Sandi KKMB Connect*\n\nKami menerima permintaan reset kata sandi untuk akun Anda.\n\nReset sekarang: {{reset_url}}\n\nJika ini bukan Anda, abaikan pesan ini."],
                'email' => ['subject' => 'Reset kata sandi KKMB Connect', 'content' => '<p>Halo {{name}},</p><p>Kami menerima permintaan reset kata sandi untuk akun Anda.</p><p><a href="{{reset_url}}" style="background:#0E7C86;color:#fff;padding:12px 18px;border-radius:12px;text-decoration:none;">Reset Kata Sandi</a></p><p>Jika ini bukan Anda, abaikan email ini.</p>'],
            ],
            'account_verified' => [
                'in_app' => ['subject' => 'Akun Anda telah diverifikasi', 'content' => 'Akun KKMB Connect Anda kini terverifikasi dan siap digunakan penuh.'],
                'whatsapp' => ['subject' => null, 'content' => "*Akun Terverifikasi*\n\nHalo {{name}}, akun Anda telah diverifikasi. Anda kini dapat menikmati fitur penuh KKMB Connect."],
                'email' => ['subject' => 'Akun Anda telah diverifikasi', 'content' => '<p>Halo {{name}},</p><p>Akun Anda telah diverifikasi dan siap digunakan penuh.</p>'],
            ],
            'member_submission' => [
                'in_app' => ['subject' => 'Pengajuan anggota baru', 'content' => 'Terdapat pengajuan anggota baru dari {{name}}. Silakan tinjau melalui panel admin.'],
                'whatsapp' => ['subject' => null, 'content' => "*Pengajuan Anggota Baru*\n\n{{name}} baru saja mendaftar di KKMB Connect.\nEmail: {{email}}\nWhatsApp: {{phone}}\nTinjau: {{admin_url}}"],
                'email' => ['subject' => 'Pengajuan anggota baru masuk', 'content' => '<p>Terdapat pengajuan anggota baru dari <strong>{{name}}</strong>.</p><p>Email: {{email}}<br>WhatsApp: {{phone}}</p><p><a href="{{admin_url}}" style="background:#0E7C86;color:#fff;padding:12px 18px;border-radius:12px;text-decoration:none;">Tinjau di Admin</a></p>'],
            ],
            'member_approved' => [
                'in_app' => ['subject' => 'Keanggotaan disetujui', 'content' => 'Selamat, keanggotaan Anda telah disetujui. Kartu digital dan fitur member kini aktif.'],
                'whatsapp' => ['subject' => null, 'content' => "*Keanggotaan Disetujui*\n\nSelamat {{name}}, keanggotaan Anda telah disetujui. Kini Anda dapat mengakses fitur member KKMB Connect."],
                'email' => ['subject' => 'Keanggotaan KKMB Anda disetujui', 'content' => '<p>Selamat {{name}},</p><p>Keanggotaan Anda telah disetujui. Kini Anda dapat mengakses fitur member KKMB Connect.</p>'],
            ],
            'payment_submitted' => [
                'in_app' => ['subject' => 'Bukti pembayaran baru', 'content' => '{{name}} baru saja mengunggah bukti pembayaran untuk ditinjau admin.'],
                'whatsapp' => ['subject' => null, 'content' => "*Bukti Pembayaran Baru*\n\n{{name}} telah mengunggah bukti pembayaran.\nNominal: {{amount}}\nTinjau: {{admin_url}}"],
                'email' => ['subject' => 'Bukti pembayaran baru untuk ditinjau', 'content' => '<p>{{name}} telah mengunggah bukti pembayaran.</p><p>Nominal: {{amount}}</p><p><a href="{{admin_url}}" style="background:#0E7C86;color:#fff;padding:12px 18px;border-radius:12px;text-decoration:none;">Tinjau Pembayaran</a></p>'],
            ],
            'payment_approved' => [
                'in_app' => ['subject' => 'Pembayaran terverifikasi', 'content' => 'Pembayaran Anda telah diverifikasi. {{message}}'],
                'whatsapp' => ['subject' => null, 'content' => "*Pembayaran Terverifikasi*\n\nHalo {{name}}, pembayaran Anda telah diverifikasi.\n{{message}}"],
                'email' => ['subject' => 'Pembayaran Anda telah diverifikasi', 'content' => '<p>Halo {{name}},</p><p>Pembayaran Anda telah diverifikasi.</p><p>{{message}}</p>'],
            ],
            'new_event' => [
                'in_app' => ['subject' => 'Event baru tersedia', 'content' => '{{event_title}} telah dipublikasikan. Cek detail dan daftar sebelum kuota penuh.'],
                'whatsapp' => ['subject' => null, 'content' => "*Event Baru KKMB*\n\n{{event_title}}\n{{event_date}}\nLokasi: {{event_location}}\nDaftar sekarang: {{event_url}}"],
                'email' => ['subject' => 'Event baru: {{event_title}}', 'content' => '<p>KKMB Connect baru saja mempublikasikan event baru:</p><h3>{{event_title}}</h3><p>{{event_date}}<br>{{event_location}}</p><p><a href="{{event_url}}" style="background:#0E7C86;color:#fff;padding:12px 18px;border-radius:12px;text-decoration:none;">Lihat Event</a></p>'],
            ],
            'event_reminder' => [
                'in_app' => ['subject' => 'Pengingat event', 'content' => '{{event_title}} akan dimulai pada {{event_date}}. Pastikan Anda siap hadir tepat waktu.'],
                'whatsapp' => ['subject' => null, 'content' => "*Pengingat Event*\n\n{{event_title}} akan dimulai pada {{event_date}}.\nLokasi: {{event_location}}\nDetail: {{event_url}}"],
                'email' => ['subject' => 'Pengingat event: {{event_title}}', 'content' => '<p>Halo {{name}},</p><p>Ini pengingat bahwa event <strong>{{event_title}}</strong> akan dimulai pada {{event_date}}.</p><p>Lokasi: {{event_location}}</p><p><a href="{{event_url}}" style="background:#0E7C86;color:#fff;padding:12px 18px;border-radius:12px;text-decoration:none;">Lihat Detail</a></p>'],
            ],
            'loan_submitted' => [
                'in_app' => ['subject' => 'Pengajuan pinjaman baru', 'content' => 'Terdapat pengajuan pinjaman baru dari {{name}} untuk ditinjau.'],
                'whatsapp' => ['subject' => null, 'content' => "*Pengajuan Pinjaman Baru*\n\n{{name}} mengajukan pinjaman sebesar {{amount}}.\nTinjau: {{admin_url}}"],
                'email' => ['subject' => 'Pengajuan pinjaman baru', 'content' => '<p>{{name}} mengajukan pinjaman sebesar {{amount}}.</p><p><a href="{{admin_url}}" style="background:#0E7C86;color:#fff;padding:12px 18px;border-radius:12px;text-decoration:none;">Tinjau Pengajuan</a></p>'],
            ],
            'loan_approved' => [
                'in_app' => ['subject' => 'Pengajuan pinjaman disetujui', 'content' => 'Pengajuan pinjaman Anda telah disetujui. Silakan cek detail lanjutan dari pengurus.'],
                'whatsapp' => ['subject' => null, 'content' => "*Pinjaman Disetujui*\n\nHalo {{name}}, pengajuan pinjaman Anda telah disetujui. Silakan cek detail lanjutan dari pengurus."],
                'email' => ['subject' => 'Pengajuan pinjaman Anda disetujui', 'content' => '<p>Halo {{name}},</p><p>Pengajuan pinjaman Anda telah disetujui. Silakan cek detail lanjutan dari pengurus.</p>'],
            ],
            'cooperative_announcement' => [
                'in_app' => ['subject' => '{{subject}}', 'content' => '{{message}}'],
                'whatsapp' => ['subject' => null, 'content' => "*{{subject}}*\n\n{{message}}\n\n{{url}}"],
                'email' => ['subject' => '{{subject}}', 'content' => '<p>{{message}}</p><p><a href="{{url}}" style="background:#0E7C86;color:#fff;padding:12px 18px;border-radius:12px;text-decoration:none;">Buka Pengumuman</a></p>'],
            ],
            'admin_broadcast' => [
                'in_app' => ['subject' => '{{subject}}', 'content' => '{{message}}'],
                'whatsapp' => ['subject' => null, 'content' => "*{{subject}}*\n\n{{message}}\n\n{{url}}"],
                'email' => ['subject' => '{{subject}}', 'content' => '<p>{{message}}</p><p><a href="{{url}}" style="background:#0E7C86;color:#fff;padding:12px 18px;border-radius:12px;text-decoration:none;">Buka Detail</a></p>'],
            ],
        ];

        $rows = [];

        foreach ($templates as $eventKey => $channels) {
            foreach ($channels as $channel => $template) {
                $rows[] = [
                    'event_key' => $eventKey,
                    'channel' => $channel,
                    'name' => str($eventKey)->replace('_', ' ')->title().' '.str($channel)->replace('_', ' ')->upper(),
                    'subject' => $template['subject'],
                    'content' => $template['content'],
                    'is_active' => true,
                    'meta' => json_encode(['seeded' => true], JSON_THROW_ON_ERROR),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        return $rows;
    }
};
