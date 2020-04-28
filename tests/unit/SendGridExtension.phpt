<?php declare(strict_types=1);

namespace Price2Performace\SendGrid\Tests;

require __DIR__ . '/../Bootstrap.php';

use Nette;
use Nette\Mail\SendmailMailer;
use Price2Performance\SendGrid\SendGridMailer;
use SendGrid;
use Tester\Assert;
use Tester\TestCase;

class SendGridExtensionTest extends TestCase
{

	/** @var \Nette\DI\Container */
	private $container;

	public function __construct(\Nette\DI\Container $container)
	{
		$this->container = $container;
	}

	public function testDI()
	{
		$container = $this->container;

		Assert::type(SendmailMailer::class, $container->getService('nette.mailer'));
		Assert::type(SendGrid::class, $container->getService('sendgrid.sendgrid'));
		Assert::type(SendGridMailer::class, $container->getService('sendgrid.mailer'));
	}

	public function testInject()
	{
		$container = $this->container;

		$presenterFactory = $container->getByType(\Nette\Application\IPresenterFactory::class);
		$presenter = $presenterFactory->createPresenter('Test');
		$presenter->autoCanonicalize = false;
		$request = new Nette\Application\Request('Test', 'GET', ['action' => 'default']);
		$response = $presenter->run($request);

		Assert::same(SendGridMailer::class, (string) $response->getSource());
	}

}

$container = Bootstrap::bootForTests()->createContainer();
(new SendGridExtensionTest($container))->run();