<?php

declare(strict_types=1);

namespace App;

use Nette\Bootstrap\Configurator;


class Bootstrap
{
	public static function boot(): Configurator
	{
		$configurator = new Configurator;
		$appDir = dirname(__DIR__);

		if (getenv('DEBUG') === 'true') {
			$configurator->setDebugMode(true);
		}
		//$configurator->setDebugMode('secret@23.75.345.200'); // enable for your remote IP
		$configurator->enableTracy($appDir . '/log');

		$configurator->setTempDirectory($appDir . '/temp');

		$configurator->createRobotLoader()
			->addDirectory(__DIR__)
			->register();

		$configurator->addDynamicParameters([
			'mysql_host' => getenv('MYSQL_HOST'),
			'mysql_user' => getenv('MYSQL_USER'),
			'mysql_password' => getenv('MYSQL_PASSWORD'),
			'mysql_database' => getenv('MYSQL_DATABASE')
		]);

		$configurator->addConfig($appDir . '/config/common.neon');
		$configurator->addConfig($appDir . '/config/services.neon');
		$configurator->addConfig($appDir . '/config/local.neon');

		return $configurator;
	}
}
