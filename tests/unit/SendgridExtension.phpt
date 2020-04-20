<?php declare(strict_types=1);

require __DIR__ . '/../Bootstrap.php';

use Haltuf\Sendgrid\SendgridMailer;
use Nette\Mail\SendmailMailer;
use Tester\Assert;
use Tester\TestCase;

class SendgridExtensionTest extends TestCase
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
		Assert::type(SendgridMailer::class, $container->getService('sendgrid.mailer'));
	}

	public function testInject()
	{
		$container = $this->container;

		$presenterFactory = $container->getByType(\Nette\Application\IPresenterFactory::class);
		$presenter = $presenterFactory->createPresenter('Test');
		$presenter->autoCanonicalize = false;
		$request = new Nette\Application\Request('Test', 'GET', ['action' => 'default']);
		$response = $presenter->run($request);

		Assert::same(SendgridMailer::class, (string) $response->getSource());
	}

}

$container = Bootstrap::bootForTests()->createContainer();
(new SendgridExtensionTest($container))->run();