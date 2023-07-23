<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthService extends AbstractService
{
    public function register(array $userData): Response|array
    {
        try {
            DB::beginTransaction();

            $user = User::create($userData);
            $responseArray = array_merge($user->toArray(), [
                'access_token' => $this->createAuthToken($user)
            ]);
            $statusCode = 201;

            DB::commit();
        } catch (Exception $e) {
            [$responseArray, $statusCode] = $this->error_when_processing();

            DB::rollback();
        }

        return $this->formatResponse($responseArray, $statusCode);
    }

    public function login(array $credentials): Response|array
    {
        try {
            if (Auth::attempt($credentials)) {
                $user = User::firstWhere('email', $credentials['email']);
                $responseArray = [
                    'access_token' => $this->createAuthToken($user)
                ];
                $statusCode = 200;
            } else {
                $responseArray = [
                    'message' => trans('messages.credentials_are_wrong')
                ];
                $statusCode = 400;
            }
        } catch (Exception $e) {
            [$responseArray, $statusCode] = $this->error_when_processing();
        }

        return $this->formatResponse($responseArray, $statusCode);
    }

    protected function createAuthToken(User $user): string
    {
        return $user->createToken($user->id)->plainTextToken;
    }
}
