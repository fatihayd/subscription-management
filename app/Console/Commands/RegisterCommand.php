<?php

namespace App\Console\Commands;

use App\Services\AuthService;
use App\Traits\CustomValidatorTrait;
use Illuminate\Console\Command;

class RegisterCommand extends Command
{
    use CustomValidatorTrait;

    protected $signature = 'make:register {--name=} {--email=} {--password=}';

    protected $description = 'This command register user';

    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        parent::__construct();
        $this->authService = $authService;
    }

    public function handle()
    {
        $name = $this->option('name');
        $email = $this->option('email');
        $password = $this->option('password');
        $userData = [
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ];

        $validator = $this->validate($userData, 'RegisterRequest');
        if ($validator['error']) {
            $this->error($validator['message']);
            return;
        }

        $response = $this->authService->register($userData);

        if (isset($response['status']) && $response['status']) {
            $this->info(trans('messages.user_registered_successfully', ['id' => $response['id']]));
            $this->info(json_encode($response));
        } else {
            $this->error($response['message']);
        }
    }
}
