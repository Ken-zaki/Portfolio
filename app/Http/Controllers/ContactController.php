<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:500',
            'message' => 'required|string|max:5000',
            'honeypot' => 'size:0', // Honeypot field should be empty
        ]);

        // Check rate limiting (simple session-based)
        if (session('last_contact_time') && (time() - session('last_contact_time')) < 60) {
            return back()->with('error', 'Please wait before sending another message.');
        }

        try {
            // Log the contact attempt
            Log::info('Contact form submission', [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'subject' => $validated['subject'],
                'ip' => $request->ip(),
            ]);

            // Here you can send email or save to database
            // For now, we'll just log it and show success
            // Uncomment below to send actual email:
            /*
            Mail::raw($validated['message'], function ($message) use ($validated) {
                $message->to('neth.zedlav@gmail.com')
                    ->subject('Portfolio Contact: ' . $validated['subject'])
                    ->replyTo($validated['email'], $validated['name']);
            });
            */

            // Update rate limiting
            session(['last_contact_time' => time()]);

            return back()->with('success', 'Thank you for your message! I will get back to you soon.');
        } catch (\Exception $e) {
            Log::error('Contact form error: ' . $e->getMessage());
            return back()->with('error', 'There was an error sending your message. Please try again.');
        }
    }
}
