<?php declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Nette\Configurator;

class Bootstrap
{
	public static function boot(): Configurator
	{
		$configurator = new Configurator;


		$configurator->setTimeZone('Europe/Prague');
		$configurator->setTempDirectory(self::getTempDir());

		$configurator->createRobotLoader()
			->addDirectory(__DIR__ . '/fixtures')
			->register();

		$configurator
			->addConfig(__DIR__ . '/unit/common.neon');

		return $configurator;
	}


	public static function bootForTests(): Configurator
	{
		\Tester\Helpers::purge(self::getTempDir());
		$configurator = self::boot();
		\Tester\Environment::setup();
		return $configurator;
	}

	public static function getTempDir(): string
	{
		return __DIR__ . '/tmp/' . (isset($_SERVER['argv']) ? md5(serialize($_SERVER['argv'])) : getmypid());
	}
}