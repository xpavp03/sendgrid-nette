<?php declare(strict_types=1);

require __DIR__ . '/../bootstrap.php';

use Haltuf\Sendgrid\SendgridMailer;
use Nette\Configurator;
use Nette\Mail\Mailer;
use Nette\Mail\SendmailMailer;
use Tester\Assert;
use Tester\TestCase;

class SendgridExtension extends TestCase
{

	public function testDI()
	{
		$container = $this->createContainer();

		Assert::type(SendmailMailer::class, $container->getService('nette.mailer'));
		Assert::type(SendGrid::class, $container->getService('sendgrid.sendgrid'));
		Assert::type(SendgridMailer::class, $container->getService('sendgrid.mailer'));
	}

	private function createContainer()
	{
		$config = new Configurator();
		$config->setTempDirectory(TEMP_DIR);
		$config->addConfig(__DIR__ . '/config.neon');

		return $config->createContainer();
	}
}

(new SendgridExtension())->run();