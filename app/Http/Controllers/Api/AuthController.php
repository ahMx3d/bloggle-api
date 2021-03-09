<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api')->only([
            'logout',
            'details',
        ]);
    }

    public function details(){
        $user = Auth::user();
        return response()->json($user, 200);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'errors'  => false,
            'message' => 'Logged out successfully!',
            'status'  => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }

    /**
     * Get new access token in case the current one is expired.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getRefreshToken(Request $request)
    {
        try {
            $refreshToken = $request->header('Refresh-Token');
            $verifyCert = app()->environment() == 'local' ? false : true;
            $url = config('app.url');
            $res = Http::withOptions([
                'verify' => $verifyCert
            ])->post("{$url}/oauth/token", [
                'grant_type'    => 'refresh_token',
                'refresh_token' => $refreshToken,
                'client_id'     => config('passport.personal_access_client.id'),
                'client_secret' => config('passport.personal_access_client.secret'),
                'scope'         => '*',
            ]);

            return response()->json($res->json(), Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Handle a login request to the application api.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) return response()->json([
            'message' => 'The given data is invalid.',
            'errors'  => $validator->errors(),
            'status'  => Response::HTTP_UNPROCESSABLE_ENTITY,
        ], Response::HTTP_UNPROCESSABLE_ENTITY);

        $data = [
            'username' => $request->username,
            'password' => $request->password
        ];

        if (Auth::attempt($data)) {
            $email = Auth::user()->email;
            return $this->refreshToken($email, $request->password);
        } else {
            return response()->json([
                'errors'  => true,
                'message' => 'Unauthorized',
                'status'  => Response::HTTP_UNAUTHORIZED,
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Handle a registration request for the application api.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name'       => ['required', 'string', 'max:255'],
            'username'   => ['required', 'string', 'max:255', 'unique:users'],
            'email'      => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'mobile'     => ['required', 'numeric', 'unique:users'],
            'password'   => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        if ($validator->fails()) return response()->json([
            'message' => 'The given data is invalid.',
            'errors'  => $validator->errors(),
            'status'  => Response::HTTP_UNPROCESSABLE_ENTITY,
        ], Response::HTTP_UNPROCESSABLE_ENTITY);

        $data = [
            'name'              => $request->name,
            'username'          => $request->username,
            'email'             => $request->email,
            'email_verified_at' => Carbon::now(),
            'mobile'            => $request->mobile,
            'status'            => 1,
            'password'          => bcrypt($request->password),
        ];

        $user = User::create($data);
        $user->attachRole(Role::whereName('user')->first()->id);

        return $this->refreshToken($request->email, $request->password);
    }

    /**
     * Refresh Authentication Token.
     *
     * @param string $email
     * @param string $password
     * @return \Illuminate\Http\JsonResponse
     */
    private function refreshToken(string $email, string $password)
    {
        $verifyCert = app()->environment() == 'local' ? false : true;
        $url = config('app.url');
        $res = Http::withOptions([
            'verify' => $verifyCert
        ])->post("{$url}/oauth/token", [
            'grant_type'    => 'password',
            'client_id'     => config('passport.personal_access_client.id'),
            'client_secret' => config('passport.personal_access_client.secret'),
            'username'      => $email,
            'password'      => $password,
            'scope'         => '*',
        ]);

        return response()->json($res->json(), Response::HTTP_OK);
    }
}
