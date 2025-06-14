<?php

namespace App\Services;

use App\Abstracts\BaseService;
use App\Contracts\AuthContract;
use App\Exceptions\CustomException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService extends BaseService implements AuthContract
{
    public function __construct()
    {
        $this->model = new User();
    }

    public function login($data): User
    {
        $user = $this->model->select(
            'id',
            'name',
            'email',
            'password'
        )
            ->where('email', $data['email'])
            ->first();

        if (!$user) {
            throw new CustomException(__('lang.messages.email_exception'));
        }

        if (!Hash::check($data['password'], $user->password)) {
            throw new CustomException(__('lang.messages.password_exception'));
        }

        return $user;
    }

    public function logout($user): bool
    {
        $user->tokens()->delete();
        return true;
    }
}
