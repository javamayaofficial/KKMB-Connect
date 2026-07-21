<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\SubscriptionPlan;
use App\Services\Payment\PaymentService;
use App\Services\Notification\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubscriptionController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::where('is_active', true)->get();
        $langganan = auth()->user()->subscriptions()->with('plan')->latest()->first();
        $tagihan = Payment::where('user_id', auth()->id())
            ->where('reference_type', 'subscription')
            ->where('status', 'pending')->latest()->first();

        return view('app.subscription.index', compact('plans', 'langganan', 'tagihan'));
    }

    // Pilih paket -> buat tagihan pending -> arahkan ke instruksi bayar
    public function subscribe(Request $request, SubscriptionPlan $plan, PaymentService $service)
    {
        abort_unless($plan->is_active, 404);

        // Paket gratis: langsung aktif tanpa pembayaran.
        if ($plan->harga <= 0) {
            $user = $request->user();
            $user->subscriptions()->create([
                'plan_id' => $plan->id,
                'status' => 'active',
                'mulai_at' => now(),
                'berakhir_at' => now()->addDays($plan->durasi_hari),
            ]);
            return redirect()->route('subscription.index')->with('status', 'Paket gratis aktif.');
        }

        [$subscription, $payment] = $service->createSubscriptionInvoice(
            $request->user(),
            $plan,
            $request->input('metode', 'manual_transfer'),
        );

        return redirect()->route('subscription.pay', $payment)->with('status', 'Silakan selesaikan pembayaran.');
    }

    public function pay(Payment $payment, PaymentService $service)
    {
        abort_unless($payment->user_id === auth()->id(), 403);
        $bank = $service->bankInfo();
        return view('app.subscription.pay', compact('payment', 'bank'));
    }

    // Upload bukti transfer (JPG/PNG/PDF, maks 2MB)
    public function uploadProof(Request $request, Payment $payment, NotificationService $notifications)
    {
        abort_unless($payment->user_id === auth()->id(), 403);

        $request->validate([
            'bukti' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        if ($payment->bukti_path && Storage::disk('public')->exists($payment->bukti_path)) {
            Storage::disk('public')->delete($payment->bukti_path);
        }

        $payment->update([
            'bukti_path' => $request->file('bukti')->store('bukti', 'public'),
            'status' => 'pending',
        ]);

        $notifications->broadcastUsers(
            $notifications->recipientsForScope('pengurus'),
            'payment_submitted',
            [
                'name' => $payment->user->name,
                'amount' => 'Rp '.number_format((int) $payment->jumlah, 0, ',', '.'),
                'admin_url' => url('/admin/payments'),
            ],
            ['in_app', 'wa', 'email'],
        );

        return redirect()->route('subscription.index')
            ->with('status', 'Bukti pembayaran terkirim. Menunggu verifikasi admin.');
    }
}
