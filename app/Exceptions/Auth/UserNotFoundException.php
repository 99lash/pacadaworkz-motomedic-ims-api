<?php

namespace App\Exceptions\Auth;

use Exception;

class UserNotFoundException extends Exception
{
    protected $message = 'No account found with this email';
    protected $code = 404;

    public function __construct(string $email)
    {
        parent::__construct("No account found with email: {$email}");
    }
}
