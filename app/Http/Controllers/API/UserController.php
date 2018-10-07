<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(Request $request)
    {
        try {
            $val = Validator::make($request->all(), [
                'name' => 'required|regex:/^[\pL\s]+$/u',
                'email' => 'required|email|unique:users,email',
                'mobile' => 'required|digits:10|unique:users,mobile',
                'password' => 'required|string',
            ],
                [
                    'name.required' => 'Please provide your name.',
                    'name.regex' => "Don't put any special characters in name.",
                    'email.required' => 'Please provide your email.',
                    'email.unique' => 'Email already taken',
                    'email.email' => 'Invalid email',
                    'mobile.required' => 'Mobile number is required.',
                    'mobile.digits' => 'Mobile number should be 10 digits',
                    'mobile.unique' => 'Mobile number is already taken',
                ]);
            if ($val->fails()) {
                return response()->json(["error" => $val->errors()->first()], 422);
            }
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);
            $success['token'] = $user->createToken('usertoken')->accessToken;
            $success['user'] = $user;
            return response()->json(['success' => $success], 200);

        } catch (Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }

    }

    public function login(Request $request)
    {
        try {
            $val = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string',
            ],
                [
                    'email.required' => 'Please provide your email.',
                    'email.email' => 'Invalid email.',
                    'password.required' => 'Please enter password',
                ]);
            if ($val->fails()) {
                return response()->json(['error' => $val->errors()->first()], 422);
            }
            $credentials = [
                'email' => $request->email, 'password' => $request->password,
            ];
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $user->clearTokens();
                $success['token'] = $user->createToken('usertoken')->accessToken;
                $success['user'] = $user;
                return response()->json(['success' => $success], 200);
            } else {
                return response()->json(['error' => 'Invalid credentials !'], 401);
            }
        } catch (Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function profile(Request $request)
    {
        return response()->json(["user" => $request->user()], 200);
    }
}
