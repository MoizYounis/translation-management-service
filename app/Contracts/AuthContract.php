<?php

namespace App\Contracts;

interface AuthContract
{
    public function login($data);
    public function logout($user);
}
