<?php declare(strict_types=1);

namespace App\Presenters;

use Nette\Mail\Mailer;
use Nette\Mail\SendmailMailer;

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