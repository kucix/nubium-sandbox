<?php
declare(strict_types=1);

namespace App\Exception;

use Nette\Security\AuthenticationException;

class UserNotFoundException extends AuthenticationException
{

}