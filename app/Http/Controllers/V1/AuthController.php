<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\BadRequestException;
use App\Exceptions\UnauthorizedException;
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
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $this->validates("login", $request);

        $token = $this->jwt->attempt($request->only('email', 'password'));
        if (! $token) {
            return $this->message('The email or password is wrong', 401);
        }

        $user = formatUser($this->jwt->user());

        return $this->respond(compact('token','user'), 200);
    }

    /**
     * Registers new users
     *
     * @param Request $request - Request object
     * @return \Illuminate\Http\Response
     *
     * @throws BadRequestException
     */
    public function signup(Request $request)
    {
        $this->validates("signup", $request);

        if(User::where("email", $request->email)->first()) {
            throw new BadRequestException("Already registered. Please Login");
        }

        $user = new User($request->all());
        $user->password = Hash::make($request->password);
        $user->save();

        return $this->message("Successful sign up. Please login");
    }
}
