# sendgrid-nette
Sendgrid integration for Nette.

[![Github badge](https://github.com/haltuf/sendgrid-nette/workflows/actions/badge.svg)](https://github.com/haltuf/sendgrid-nette/actions) [![Build Status](https://travis-ci.org/haltuf/sendgrid-nette.svg?branch=master)](https://travis-ci.org/haltuf/sendgrid-nette) [![Coverage Status](https://coveralls.io/repos/haltuf/sendgrid-nette/badge.svg)](https://coveralls.io/r/haltuf/sendgrid-nette)    

## Install
```
composer require haltuf/sendgrid-nette
```

## Configuration
In config add:

```
extension:
    sendgrid: Haltuf\Sendgrid\SendgridExtension

sendgrid:
    key: 'SECRET_KEY'
```

## Usage
To make any API call to Sendgrid, just inject the Sendgrid class to your presenter:

```php
    /** @var SendGrid @inject */
    public $sendgrid;
	
    public function actionDefault()
    {
        // CALL suppression/bounces
        try {
            $response = $this->sendgrid->client->suppression()->bounces()->get();
            print $response->statusCode() . "\n";
            print_r($response->headers());
            print $response->body() . "\n";
        } catch (Exception $e) {
            echo 'Caught exception: '.  $e->getMessage(). "\n";
        }
    }
	
```

To send an email via `Sendgrid`, just inject `Haltuf\Sendgrid\SendgridMailer` to your presenter:

```php
    /** @var \Haltuf\Sendgrid\SendgridMailer @inject */
    public $mailer;
	
    protected function sendMail() {
        
        $message = new \Nette\Mail\Message();
        $message->addTo('example@example.com');
        $message->setSubject('TEST SUBJECT');
        $message->setBody('TEST BODY');
        
        $this->mailer->send($message);
        
        /** @var \SendGrid\Response $response */
        $response = $this->mailer->getLastResponse();   // optional, for error logging
    }
	
```

Calling `getLastResponse()` on `SendgridMailer` gets you `Sendgrid\Response` of the last `send()` call. You can use it to log errors.

## Versions

|Version|Nette|Sendgrid API|PHP
|---|:---:|:---:|:---:|
|master|^3.0|^7.4|^7.1