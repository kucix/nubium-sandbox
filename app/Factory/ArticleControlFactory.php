<?php
declare(strict_types=1);

namespace App\Factory;

use App\Controls\Article\ArticleControl;

interface ArticleControlFactory
{
	function create(): ArticleControl;
}