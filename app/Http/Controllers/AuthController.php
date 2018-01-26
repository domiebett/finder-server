<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{

    protected $jwt;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    /**
     * Login user
     *
     * @param Request $request - Request object
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email'    => 'required|email|max:255',
            'password' => 'required',
        ]);


        try {
            if (! $token = $this->jwt->attempt($request->only('email', 'password'))) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (JWTException $e) {
            return response()->json(['token_absent' => $e->getMessage()], $e->getStatusCode());
        }

        $user = $request->user();
        return response()->json(compact('token', 'user'), 200);
    }

    /**
     * Registers new users
     *
     * @param Request $request - Request object
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            "name" => "required|max:100",
            "email"    => "required|email|max:255",
            "password" => "required",
        ]);

        try {
            $user = new User(
                [
                    "user_name" => $request->input("name"),
                    "email" => $request->input("email"),
                ]
            );
            $user->password = Hash::make(
                $request->input($request->input("password"))
            );
            $user->save();
        }
        catch(QueryException $e) {
            return response()->json([
                "message" => $e->getMessage()
            ], 400);
        }

        return $this->login($request);
    }
}
