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
use Illuminate\Validation\ValidationException;

class AuthenticationController extends Controller
{
    /**
     * Login a user and return a token.
     * @param LoginRequest $request
     * @return JsonResponse
     * @throw ValidationException
     * @throws Exception
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {

            $user = User::query()
                ->where('email', $request->input('email'))
                ->first();

            if (!$user) {
                throw ValidationException::withMessages(['email' => ['The provided credentials are incorrect.']]);
            }

            // Check if the provided password matches the hashed password in the database
            if (!Hash::check($request->input('password'), $user->password)) {
                throw ValidationException::withMessages(['email' => ['The provided credentials are incorrect.']]);
            }

            // Revoke all previous tokens
            $user->tokens()->delete();

            // Create a new token
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json(['message' => 'Login successful', 'token' => $token, 'user' => $user]);

        } catch (ValidationException $e) {

            Log::error($e->getMessage());

            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);

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

    /**
     * Get the authenticated user.
     * @param Request $request
     * @return JsonResponse
     */
    public function user(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }
}
