<?php

namespace App\Enum;

class ResponseMessages
{
    const OK = 'OK';

    const FOUND = 'Found';

    const SUCCESSFUL = 'true';

    const ERROR = 'Record not found';

    const DELETED = 'Deleted';

    const LOGIN = 'login';

    const LOGOUT = 'logout';

    const CREATED = 'register';

    const CREATE = 'created';

    const UPDATED = 'Updated';

    const MESSAGE_500 = 'Something went wrong, please try again';

    const INVALID_CRED = 'Invalid credentials';
}
