<?php

namespace Istrix\Mail;

use Nette\Mail\IMailer;
use Nette\Mail\Message;
use Nette\SmartObject;
use SendGrid;
use SendGrid\Mail\Mail;

class SendgridMailer implements IMailer
{
    const ENDPOINT = "https://api.sendgrid.com/";

    use SmartObject;

    /** @var string */
    private $key;

    /** @var string */
    private $tempFolder;

    /** @var array */
    private $tempFiles = [];

    /**
     * MailSender constructor
     *
     * @param string $key
     * @param string $tempFolder
     */
    public function __construct($key, $tempFolder)
    {
        $this->key = $key;
        $this->tempFolder = $tempFolder;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * Sends email to sendgrid
     *
     * @param Message $message
     *
     * @throws SendGrid\Exception
     */
    public function send(Message $message): void
    {
        $sendGrid = new SendGrid($this->key);
        $email = new Mail();

        $from = $message->getFrom();
        reset($from);
        $key = key($from);

        $email->setFrom($key, $from[$key]);
        $email->setSubject($message->getSubject());
        $email->addContent('text/plain', $message->getBody());
        $email->addContent('text/html', $message->getHtmlBody());

        foreach ($message->getAttachments() as $attachement) {
            $header = $attachement->getHeader('Content-Disposition');
            preg_match('/filename\=\"(.*)\"/', $header, $result);
            $originalFileName = $result[1];

            $filePath = $this->saveTempAttachement($attachement->getBody());

            $email->addAttachment($filePath, $originalFileName);
        }

        foreach ((array)$message->getHeader('To') as $recipient => $name) {
            $email->addTo($recipient, $name);
        }

        foreach ((array)$message->getHeader('Cc') as $recipient => $name) {
            $email->addCc($recipient, $name);
        }

        foreach ((array)$message->getHeader('Bcc') as $recipient => $name) {
            $email->addBcc($recipient, $name);
        }

        $sendGrid->send($email);

        $this->cleanUp();
    }

    private function saveTempAttachement($body)
    {
        $filePath = $this->tempFolder . '/' . md5($body);
        file_put_contents($filePath, $body);
        array_push($this->tempFiles, $filePath);

        return $filePath;
    }

    private function cleanUp()
    {
        foreach ($this->tempFiles as $file) {
            unlink($file);
        }
    }

}
