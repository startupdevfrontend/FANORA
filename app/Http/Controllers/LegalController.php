<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class LegalController extends Controller
{
    public function terms(): View
    {
        return view('legal.terms');
    }

    public function privacy(): View
    {
        return view('legal.privacy');
    }

    public function contentPolicy(): View
    {
        return view('legal.content-policy');
    }

    public function cookies(): View
    {
        return view('legal.cookies');
    }

    public function contact(): View
    {
        return view('legal.contact');
    }

    public function contactSubmit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:200'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        // SECURITY: sanitize to prevent header injection & XSS in logs
        $safeName = str_replace(["\r", "\n", "%0a", "%0d"], '', strip_tags($validated['name']));
        $safeSubject = str_replace(["\r", "\n", "%0a", "%0d"], '', strip_tags($validated['subject']));
        $safeMessage = strip_tags($validated['message']);

        // MVP: logs the message via the default mailer (log in dev).
        Mail::raw(
            "De: {$safeName} <{$validated['email']}>\nAssunto: {$safeSubject}\n\n{$safeMessage}",
            fn ($message) => $message
                ->to(config('mail.from.address'))
                ->subject('[FANORA Contato] '.$safeSubject)
        );

        return back()->with('status', 'Mensagem enviada. Entraremos em contato em breve.');
    }
}