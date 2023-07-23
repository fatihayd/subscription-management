<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        if((app()->runningInConsole() || app()->runningUnitTests()) && !$this->checkDbConnection()){
            die('Veritabanına bağlanılamadı. Lütfen veritabanı ayarlarını kontrol edin.');
        }
        $this->renderable(function (AuthenticationException $e, $request) {
            return response()->json([
                'message' => trans('messages.unauthorized')
            ], 401);
        });
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    private function checkDbConnection():bool
    {
        $commands = \Request::server('argv', null);
        if(isset($commands[1]) && $commands[1] == 'key:generate'){
            return true;
        }
        try {
            DB::connection()->getPDO();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    protected function invalidJson($request, ValidationException $exception)
    {
        $errorCount = count($exception->errors()) - 1;
        return response([
            'message' => str_replace(
                '(and 1 more error)',
                trans('messages.one_or_more_error', ['count' => $errorCount]),
                $exception->getMessage()
            ),
            'errors' => $exception->errors(),
        ], $exception->status);
    }
}
