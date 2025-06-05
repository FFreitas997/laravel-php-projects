<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthenticationController extends Controller
{

    /**
     * Login a user and return a token.
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {

            $user = User::query()
                ->where('email', $request->input('email'))
                ->first();

            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            if (!Hash::check($request->input('password'), $user->password)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            $user->tokens()->delete(); // Revoke all previous tokens

            // Create a new token
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json(['message' => 'Login successful', 'token' => $token, 'user' => $user]);

        } catch (Exception $e) {

            Log::error($e->getMessage());

            return response()->json(['message' => 'An internal error occurred', 'error' => $e->getMessage()], 500);

        }
    }

    /**
     * Logout
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function logout(Request $request): JsonResponse
    {
        try {

            $user = $request->user();

            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            // Revoke all tokens
            $user->tokens()->delete();

            return response()->json(['message' => 'Logout successful']);

        } catch (Exception $e) {

            Log::error($e->getMessage());

            return response()->json(['message' => 'An internal error occurred', 'error' => $e->getMessage()], 500);

        }
    }
}
