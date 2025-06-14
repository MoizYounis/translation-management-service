<?php

namespace App\Contracts;

use App\Models\User;

interface AuthContract
{
    public function login($data): User;
    public function logout($user): bool;
}
