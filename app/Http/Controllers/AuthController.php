<?php

namespace App\Http\Controllers;

use App\Helpers\CreateResponseMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    // Register function
    public function register(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            // 'password' => 'required|min:5|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            'password' => 'required|min:5',
            'password_confirmation' => 'required|same:password',
        ], [
            'name.required' => 'name_required',
            'email.required' => 'email_required',
            'email.email' => 'email_not_available_email',
            'email.unique' => 'email_not_unique',
            'password.required' => 'password_required',
            'password.min' => 'password_min_8',
            // 'password.regex' => 'password_regex',
            'password_confirmation.required' => 'password_confirmation_required',
            'password_confirmation.same' => 'password_confirmation_same',
        ]);

        if ($validator->fails())
            return response()->json(CreateResponseMessage::Error('error_in_validate', $validator->errors()), 500);
        else {

            try {
                // Create the user
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password), // Hash the password before saving
                ]);

                // Optionally, log the user in immediately after registration
                Auth::login($user);

                // Return a success response
                return response()->json(CreateResponseMessage::Success('registration_successful', $user), 201);
            } catch (\Exception $error) {

                return response()->json(CreateResponseMessage::Error('error_in_create_user', $error), 500);
            }
        }
    }

    // Login function
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return response()->json(['message' => 'Login successful'], 200);
        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }

    // Logout function
    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
