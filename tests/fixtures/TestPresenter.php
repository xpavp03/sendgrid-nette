<?php declare(strict_types=1);

use Nette\Mail\Mailer;

class TestPresenter extends \Nette\Application\UI\Presenter
{
	/** @var \Haltuf\Sendgrid\SendgridMailer @inject */
	public $mailer;

	/** @var Mailer @inject */
	public $mailer2;

	public function actionDefault()
	{
		$a = get_class($this->mailer2);
		$this->template->className = get_class($this->mailer);
	}
}