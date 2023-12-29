<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }

        } catch (JWTException $e) {
            return response()->json(ResponseFormatter::format(500, "FAILED CREATE TOKEN", null), 500);
        }

        $payload = JWTAuth::manager()->getJWTProvider()->decode($token);
        $expiresAt = $payload['exp'] ?? null;

        return response()->json(ResponseFormatter::format(200, "OK", compact('expiresAt','token')), 200);
    }

    public function register(RegisterRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
            ]);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }

        return response()->json(ResponseFormatter::format(200, "OK", compact('user')), 200);
    }

    public function show()
    {
        try {
            $user = User::get();
            return response()->json(ResponseFormatter::format(200, "OK", $user), 200);
        } catch (\Exception $e) {
            return response()->json(ResponseFormatter::format($e->getCode(), $e->getMessage()), $e->getCode());
        }
    }
}