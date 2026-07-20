<?php

namespace App\Services\Payment;

use App\Models\Payment;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Services\Notification\NotificationService;
use Illuminate\Support\Facades\DB;

/**
 * PaymentService MVP: MANUAL TRANSFER / QRIS MANUAL.
 * Alur: buat tagihan (pending) -> user upload bukti -> admin verifikasi -> aktifkan langganan.
 * Gateway (Midtrans) disiapkan sebagai fase 2 lewat provider terpisah, tanpa mengubah alur ini.
 */
class PaymentService
{
    public function __construct(protected NotificationService $notifications) {}

    /**
     * Buat tagihan langganan untuk sebuah paket. Mengembalikan pasangan [Subscription, Payment].
     */
    public function createSubscriptionInvoice(User $user, SubscriptionPlan $plan, string $metode = 'manual_transfer'): array
    {
        return DB::transaction(function () use ($user, $plan, $metode) {
            $subscription = Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'status' => 'pending',
            ]);

            $payment = Payment::create([
                'user_id' => $user->id,
                'reference_type' => 'subscription',
                'reference_id' => $subscription->id,
                'jumlah' => $plan->harga,
                'metode' => $metode,
                'status' => 'pending',
            ]);

            return [$subscription, $payment];
        });
    }

    /**
     * Verifikasi pembayaran oleh admin -> aktifkan langganan sesuai durasi paket.
     */
    public function verify(Payment $payment, User $admin, ?string $catatan = null): void
    {
        DB::transaction(function () use ($payment, $admin, $catatan) {
            $payment->update([
                'status' => 'verified',
                'verified_by' => $admin->id,
                'verified_at' => now(),
                'catatan_admin' => $catatan,
            ]);

            if ($payment->reference_type === 'subscription' && $payment->reference_id) {
                $subscription = Subscription::with('plan')->find($payment->reference_id);
                if ($subscription) {
                    $mulai = now();
                    $subscription->update([
                        'status' => 'active',
                        'mulai_at' => $mulai,
                        'berakhir_at' => $mulai->copy()->addDays($subscription->plan->durasi_hari),
                    ]);

                    $this->notifications->notify(
                        $subscription->user,
                        'Langganan Aktif',
                        "Pembayaran paket {$subscription->plan->nama} telah diverifikasi. Langganan aktif hingga {$subscription->berakhir_at->format('d M Y')}.",
                        ['in_app', 'wa', 'email'],
                    );
                }
            }
        });
    }

    public function reject(Payment $payment, User $admin, ?string $catatan = null): void
    {
        $payment->update([
            'status' => 'rejected',
            'verified_by' => $admin->id,
            'verified_at' => now(),
            'catatan_admin' => $catatan,
        ]);

        if ($payment->reference_type === 'subscription' && $payment->reference_id) {
            Subscription::where('id', $payment->reference_id)->update(['status' => 'rejected']);
        }

        $this->notifications->notify(
            $payment->user,
            'Pembayaran Ditolak',
            'Mohon maaf, bukti pembayaran Anda belum dapat diverifikasi. '.($catatan ?: 'Silakan hubungi pengurus.'),
            ['in_app', 'email'],
        );
    }

    public function bankInfo(): array
    {
        return [
            'bank' => config('integrations.payment.bank_name'),
            'no_rekening' => config('integrations.payment.bank_account_no'),
            'atas_nama' => config('integrations.payment.bank_account_name'),
            'qris' => config('integrations.payment.qris_image_path'),
        ];
    }
}
