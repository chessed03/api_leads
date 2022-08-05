<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

/**
* @OA\Info(title="API Leads", version="1.0")
*
* @OA\Server(url="http://127.0.0.1:8000")
*/

class AuthController extends Controller
{

    public function reponseApi( $data, $status )
    {
        
        $response = [
            'status'   => $status,
            'response' => [
                'result' => $data
            ]
        ];
        
        return response( $response );

    }

    /**
    * @OA\Post(
    * path="/api/register",
    * operationId="Register new user",
    * tags={"Register new user"},
    * summary="Register New User",
    * description="Register New User",
    *     @OA\RequestBody(
    *         @OA\JsonContent(),
    *         @OA\MediaType(
    *            mediaType="multipart/form-data",
    *            @OA\Schema(
    *               type="object",
    *               required={"name", "email", "password", "password_confirmation"},
    *               @OA\Property(property="name", type="text"),
    *               @OA\Property(property="email", type="email"),
    *               @OA\Property(property="password", type="password"),
    *               @OA\Property(property="password_confirmation", type="password")
    *            ),
    *        ),
    *    ),
    *
    *      @OA\Response(
        *          response=201,
        *          description="Login Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Login Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
    *
    *
    * )
    */

    public function register(Request $request) 
    {
        
        $fields = $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name'     => $fields['name'],
            'email'    => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        /*$token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user'  => $user,
            'token' => $token
        ];*/

        $response = [
            'user'  => $user
        ];

        return $this->reponseApi( $response, 200 );

    }

    /**
    * @OA\Post(
    * path="/api/login",
    * operationId="Login",
    * tags={"Login"},
    * summary="User Login",
    * description="Login User Here",
    *     @OA\RequestBody(
    *         @OA\JsonContent(),
    *         @OA\MediaType(
    *            mediaType="multipart/form-data",
    *            @OA\Schema(
    *               type="object",
    *               required={"email", "password"},
    *               @OA\Property(property="email", type="email"),
    *               @OA\Property(property="password", type="password")
    *            ),
    *        ),
    *    ),
    *      @OA\Response(
    *          response=201,
    *          description="Login Successfully",
    *          @OA\JsonContent()
    *       ),
    *
    *      @OA\Response(response=400, description="Bad request"),
    * )
    */

    public function login(Request $request) 
    {
        
        $fields = $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string'
        ]);

        // Check email
        $user = User::where('email', $fields['email'])->first();

        // Check password
        if( !$user || !Hash::check($fields['password'], $user->password) ) {
            
            return $this->reponseApi( 'Bad request', 400 );

        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user'  => $user,
            'token' => $token
        ];

        return $this->reponseApi( $response, 200 );

    }

    public function logout(Request $request) 
    {
        
        auth()->user()->tokens()->delete();
        
        return $this->reponseApi( 'Logged out', 200 );

    }
}
