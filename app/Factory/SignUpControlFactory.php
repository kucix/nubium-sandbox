<?php
declare(strict_types=1);

namespace App\Factory;

use App\Controls\SignUp\SignUpControl;

interface SignUpControlFactory
{
	function create(): SignUpControl;
}