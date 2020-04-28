<?php declare(strict_types=1);

class TestPresenter extends \Nette\Application\UI\Presenter
{
	/** @var \Price2Performance\SendGrid\SendGridMailer @inject */
	public $mailer;

	/** @var \Nette\DI\Container @inject */
	public $container;

	public function actionDefault()
	{
		if (class_exists('Nette\Mail\Mailer')) {
			$defaultMailer = $this->container->getByType('Nette\Mail\Mailer');
		} else {
			$defaultMailer = $this->container->getByType('Nette\Mail\IMailer');
		}

		$this->template->className = get_class($this->mailer);
	}
}