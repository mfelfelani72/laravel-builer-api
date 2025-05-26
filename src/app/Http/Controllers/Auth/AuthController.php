<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\CreateResponseMessage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    // Register function
    public function register(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            // 'name' => 'required',
            'email' => 'required|email|unique:users',
            // 'password' => 'required|min:5|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            'password' => 'required|min:5',
            'password_confirmation' => 'required|same:password',
        ], [
            // 'name.required' => 'name_required',
            'email.required' => 'email_required',
            'email.email' => 'email_not_available_email',
            'email.unique' => 'email_not_unique',
            'password.required' => 'password_required',
            'password.min' => 'password_min_5',
            // 'password.regex' => 'password_regex',
            'password_confirmation.required' => 'password_confirmation_required',
            'password_confirmation.same' => 'password_confirmation_same',
        ]);


        if ($validator->fails())
            return response()->json(CreateResponseMessage::Error('error_in_validate', $validator->errors()), 200);
        else {

            try {
                // Create the user
                $user = User::create([
                    // 'name' => $request->name,
                    'name' => $request->email,
                    'email' => $request->email,
                    'password' => Hash::make($request->password), // Hash the password before saving
                ]);

                // Optionally, log the user in immediately after registration
                Auth::login($user);
                $user = Auth::user();

                $user->tokens()->delete();
                $token = $user->createToken('auth_token')->plainTextToken;

                // Return a success response

                return response()->json(CreateResponseMessage::Success("registration_successful", json_decode((json_encode([
                    "token" => $token,
                    "user" => ["id" => $user->id, "email" => $user->email, "name" => $user->name, "email_verified_at" => $user->email_verified_at]
                ])))), 201);
            } catch (\Exception $error) {

                return response()->json(CreateResponseMessage::Error('error_in_create_user', $error), 500);
            }
        }
    }

    // Login function
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(CreateResponseMessage::Error("user_didnt_find",  json_decode(json_encode(["error_code" => "401"]))), 200);
        }

        $user = Auth::user();

        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(CreateResponseMessage::Success("user_is_login", json_decode((json_encode([
            "token" => $token,
            "user" => ["id" => $user->id, "email" => $user->email, "name" => $user->name, "email_verified_at" => $user->email_verified_at]
        ])))), 200);
    }

    // Logout function
    public function logout(Request $request)
    {
        try {

            $request->user()->currentAccessToken()->delete();
            return response()->json(
                CreateResponseMessage::Success("user_logout", new \stdClass()),
                200
            );
        } catch (\Exception $error) {
            return response()->json(CreateResponseMessage::Error("error_in_logout", $error), 500);
        }
    }
}
