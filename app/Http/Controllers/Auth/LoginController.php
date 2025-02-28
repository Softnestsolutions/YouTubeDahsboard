<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Authenticate user and return API response with token.
     */
    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user = Auth::user();

                // Update last login timestamp
                $user->update(['last_login' => Carbon::now()->toDateTimeString()]);

                return response()->json([
                    'success' => true,
                    'message' => 'Login successful',
                    'data'    => [
                        'user_id'      => $user->id,
                        'name'         => $user->name,
                        'email'        => $user->email,
                        'join_date'    => $user->join_date,
                        'last_login'   => $user->last_login,
                        'phone_number' => $user->phone_number,
                        'status'       => $user->status,
                        'role_name'    => $user->role_name,
                        'avatar'       => $user->avatar,
                        'position'     => $user->position,
                        'department'   => $user->department
                    ],
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password',
            ], 401);
        } catch (\Exception $e) {
            Log::error('Authentication error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.',
            ], 500);
        }
    }

    /**
     * Logout the user and revoke API token.
     */
    public function logout(Request $request)
    {
        try {
            if (Auth::check()) {
                $user = Auth::user();
                $user->tokens()->delete(); // Revoke all tokens (Sanctum)
                Auth::logout();
            }

            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Logout error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to logout. Try again.',
            ], 500);
        }
    }
}
