<?php
declare(strict_types=1);

namespace App\Factory;

use App\Controls\User\UserControl;

interface UserControlFactory
{
	function create(): UserControl;
}