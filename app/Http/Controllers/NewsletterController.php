<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsletterSubscribeRequest;
use App\Mail\NewsletterConfirmation;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    public function subscribe(NewsletterSubscribeRequest $request)
    {
        $email = strtolower(trim($request->email));

        $existing = NewsletterSubscriber::where('email', $email)->first();

        if ($existing && $existing->isActive()) {
            return response()->json([
                'success' => true,
                'message' => 'Email Anda sudah terdaftar dan aktif.',
            ]);
        }

        if ($existing && $existing->status === 'unsubscribed') {
            $existing->update([
                'status' => 'pending',
                'token' => NewsletterSubscriber::generateToken(),
                'unsubscribed_at' => null,
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 500),
            ]);

            $this->sendConfirmationEmail($existing);

            return response()->json([
                'success' => true,
                'message' => 'Silakan cek email Anda untuk konfirmasi.',
            ]);
        }

        if ($existing && $existing->isPending()) {
            $existing->update([
                'token' => NewsletterSubscriber::generateToken(),
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 500),
            ]);

            $this->sendConfirmationEmail($existing);

            return response()->json([
                'success' => true,
                'message' => 'Email konfirmasi telah dikirim ulang. Cek inbox Anda.',
            ]);
        }

        $subscriber = NewsletterSubscriber::create([
            'email' => $email,
            'name' => $request->name,
            'status' => 'pending',
            'token' => NewsletterSubscriber::generateToken(),
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
        ]);

        $this->sendConfirmationEmail($subscriber);

        return response()->json([
            'success' => true,
            'message' => 'Terima kasih! Silakan cek email Anda untuk konfirmasi.',
        ]);
    }

    public function verify(string $token)
    {
        $subscriber = NewsletterSubscriber::where('token', $token)->first();

        if (! $subscriber) {
            return view('site.newsletter.verify', [
                'status' => 'invalid',
                'message' => 'Link verifikasi tidak valid atau sudah kedaluwarsa.',
            ]);
        }

        if ($subscriber->isActive()) {
            return view('site.newsletter.verify', [
                'status' => 'already',
                'message' => 'Email Anda sudah terverifikasi sebelumnya.',
            ]);
        }

        $subscriber->update([
            'status' => 'active',
            'verified_at' => now(),
            'token' => null,
        ]);

        return view('site.newsletter.verify', [
            'status' => 'success',
            'message' => 'Terima kasih! Email Anda sudah terverifikasi dan terdaftar di newsletter kami.',
        ]);
    }

    public function unsubscribePage(string $token)
    {
        $subscriber = NewsletterSubscriber::where('token', $token)->first();

        if (! $subscriber) {
            return view('site.newsletter.unsubscribe', [
                'status' => 'invalid',
                'message' => 'Link unsubscribe tidak valid.',
                'token' => null,
            ]);
        }

        return view('site.newsletter.unsubscribe', [
            'status' => 'confirm',
            'message' => 'Klik tombol di bawah untuk berhenti berlangganan newsletter.',
            'token' => $token,
        ]);
    }

    public function unsubscribe(Request $request, string $token)
    {
        $subscriber = NewsletterSubscriber::where('token', $token)->first();

        if (! $subscriber) {
            return redirect()->route('frontend.home')->with('error', 'Link unsubscribe tidak valid.');
        }

        $subscriber->update([
            'status' => 'unsubscribed',
            'unsubscribed_at' => now(),
            'token' => null,
        ]);

        return view('site.newsletter.unsubscribe', [
            'status' => 'done',
            'message' => 'Anda telah berhasil berhenti berlangganan. Terima kasih.',
            'token' => null,
        ]);
    }

    protected function sendConfirmationEmail(NewsletterSubscriber $subscriber): void
    {
        try {
            Mail::to($subscriber->email)
                ->send(new NewsletterConfirmation($subscriber));
        } catch (\Throwable $e) {
            \Log::error('Newsletter confirmation failed', [
                'email' => $subscriber->email,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
