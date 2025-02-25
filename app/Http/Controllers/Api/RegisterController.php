<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Jobs\SendWelcomeEmail;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Save the user
        $user = User::create([
            'email' => $request->email,
        ]);

        // Dispatch the job to send the email
        SendWelcomeEmail::dispatch($user);

        return response()->json(['message' => 'User registered successfully!'], 201);
    }
}
