<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class AuthController extends Controller
{
    /**
     * Register a new user and return an authentication token.
     */
    public function register(Request $request)
    {
        // Validate the request inputs
        $validator = Validator::make($request->all(), [
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users',
            'password'              => 'required|string|min:6|confirmed', // expects a 'password_confirmation' field too
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Create the user
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Create an API token for the user
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully.',
            'token'   => $token,
        ], 201);
    }

    /**
     * Log in an existing user and return an authentication token.
     */
    public function login(Request $request)
{
    $credentials = $request->validate([
        'email'    => 'required|email',
        'password' => 'required|string|min:6',
    ]);

    // Attempt to create a JWT for these credentials
    if (! $token = JWTAuth::attempt($credentials)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $user  = Auth::user();
    $roles = $user->getRoleNames()->toArray();  // Spatie roles

    return response()->json([
        'message'      => 'Login successful.',
        'access_token' => $token,    // <-- real JWT here
        'user'         => [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'roles' => $roles,
        ],
    ]);
}

    /**
     * Handle forgot password by sending a verification code.
     */
    public function forgotPassword(Request $request)
    {
        // Validate the email address
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $email = $request->email;

        // Generate a 6-digit verification code
        $verificationCode = rand(100000, 999999);

        // Optionally, you can hash the code for security before storing
        // $hashedCode = Hash::make($verificationCode);

        // Store the verification code in the password_resets_verification table
        DB::table('password_resets_verification')->updateOrInsert(
            ['email' => $email],
            [
                'verification_code' => $verificationCode,
                'created_at' => Carbon::now()
            ]
        );

        // Send the verification code via email
        // Make sure you have configured your Mail settings in .env
        Mail::send('emails.verification_code', ['code' => $verificationCode], function($message) use ($email) {
            $message->to($email)
                    ->subject('Your Password Reset Verification Code');
        });

        return response()->json([
            'message' => 'Verification code sent to your email address.'
        ], 200);
    }
     // Reset password: verify code, update password, and delete the verification record.

    public function resetPassword(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email'             => 'required|email|exists:users,email',
        'verification_code' => 'required|numeric',
        'password'          => 'required|string|min:6|confirmed', // expects 'password_confirmation'
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    $record = DB::table('password_resets_verification')
                ->where('email', $request->email)
                ->first();

    if (!$record || $record->verification_code != $request->verification_code) {
        return response()->json(['message' => 'Invalid verification code.'], 400);
    }

    // Optionally, check for code expiration (e.g., valid for 15 minutes)
    $expiresAt = Carbon::parse($record->created_at)->addMinutes(15);
    if (Carbon::now()->greaterThan($expiresAt)) {
        return response()->json(['message' => 'Verification code expired.'], 400);
    }

    // Update the user's password
    $user = \App\Models\User::where('email', $request->email)->first();
    $user->password = Hash::make($request->password);
    $user->save();

    // Delete the used verification code record
    DB::table('password_resets_verification')->where('email', $request->email)->delete();

    return response()->json(['message' => 'Password reset successfully.'], 200);
}


    /**
     * Log out the user by revoking the token.
     */
    public function logout(Request $request)
    {
        // Revoke the token that was used to authenticate the current request
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully.'
        ], 200);
    }
}
