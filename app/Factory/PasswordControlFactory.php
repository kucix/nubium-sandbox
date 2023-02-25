<?php
declare(strict_types=1);

namespace App\Factory;

use App\Controls\Password\PasswordControl;

interface PasswordControlFactory
{
	function create(): PasswordControl;
}