<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;

class EmailController extends Controller
{
    public function sendEmail(Request $request)
    {
        $email = $request->input('email');

        // Validate the email address...
        $request->validate([
            'email' => 'required|email',
        ]);

        // Send the email...
        Mail::to($email)->send(new SendEmail);

        return response()->json(['message' => 'Email sent successfully']);
    }
}