<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
        
    /**
     * Obtain the user information from Google.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleGoogleCallback(Request $request): JsonResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if the user already exists
            $findUser = User::where('email', $googleUser->email)->first();
            
            if ($findUser) {
                // If user exists, log them in
                Auth::login($findUser);
            } else {
                // If user does not exist, create a new user
                $newUser = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id'=> $googleUser->gooogle_id,
                    'password' => bcrypt('123456dummy') // You can set a default password or generate a random one
                ]);

                Auth::login($newUser);
                $findUser = $newUser; // Update $findUser to the newly created user
            }

            // Generate JWT token
            $token = auth()->login($findUser);

            // Return JSON response with token
            return response()->json([
                'token' => $token,
                'user' => $findUser // Optionally, you can return user information
            ]);

        } catch (Exception $e) {
            // Handle exceptions
            Log::error('Google OAuth Callback Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to login with Google',
                'message' => $e->getMessage()
            ], 500); // Internal Server Error
        }
    }
}
