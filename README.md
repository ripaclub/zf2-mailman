# ZF2 Mail Manager

[![Packagist](https://img.shields.io/packagist/v/ripaclub/zf2-mailman.svg?style=flat-square)](https://packagist.org/packages/ripaclub/zf2-mailman) [![Travis branch](https://img.shields.io/travis/ripaclub/zf2-mailman/master.svg?style=flat-square)](https://travis-ci.org/ripaclub/zf2-mailman) [![Coveralls branch](https://img.shields.io/coveralls/ripaclub/zf2-mailman/master.svg?style=flat-square)](https://coveralls.io/r/ripaclub/zf2-mailman?branch=master)

## What is this?

This is a ZF2 Module that gives you a simple way to configure one or multiple mail services.

It supports [all transports](https://github.com/zendframework/zf2/tree/master/library/Zend/Mail/Transport) shipped with ZF2, e.g. any transport that implements the `Zend\Mail\Transport\TransportInterface`.

It also has a transport for [Mandrill](http://mandrill.com). If you wish to use it install also `mandrill/mandrill` (versions 1.0.*) library.

## Installation

Add `ripaclub/zf2-mailman` to your `composer.json`.

```
{
   "require": {
       "ripaclub/zf2-mailman": "v0.2.0"
   }
}
```

## Usage

Configure a transport in your configuration file.

```php
'mailman' => [
    'MailMan\Gmail' => [
        'default_sender' => 'my-name-is-methos@gmail.com',
        'transport' => [
            'type' => 'smtp',
            'options' => [
                 'host' => 'smtp.gmail.com',
                 'port' => '587',
                 'connection_class' => 'login',
                 'connection_config' => [
                     'ssl' => 'tls',
                     'username' => 'my-name-is-methos@gmail.com',
                     'password' => 'MYSECRETPASSWORD',
                 ]
             ]
        ],
    ],
],
```

**Text only message**

Then we send a text only message.

```php
$message = new MailMan\Message();
$message->addTextPart('Test email');
$message->setSubject('My name is methos');
$message->addFrom('my-name-is-methos@gmail.com', 'Methos');
$message->addTo('ripaclub@gmail.com', 'RipaClub');
/** @var \MailMan\Service\MailService $mailService */
$mailService = $this->getServiceLocator()->get('MailMan\Gmail');
$mailService->send($message);
```

**Message with attachment**

Do you want to send an email message with an attachment from filesystem?

```php
$message = new MailMan\Message();
$message->addAttachment('/path/to/an/attachment.png');
$message->setBody('Test email');
$message->setSubject('My name is methos');
$message->addFrom('my-name-is-methos@gmail.com', 'Methos');
$message->addTo('ripaclub@gmail.com', 'RipaClub');
/** @var \MailMan\Service\MailService $mailService */
$mailService = $this->getServiceLocator()->get('MailMan\Gmail');
$mailService->send($message);
```

**Message using a template**

```php
$content = new ViewModel();
$content->setTemplate('email/example.phtml');
$content->setVariable('name', 'RipaClub');
$message = new MailMan\Message();
$message->setSubject('Example email');
$message->addHtmlPart($this->getServiceLocator()->get('ViewRenderer')->render($content));
$message->addTo('ripaclbu@gmail.com', 'RipaClub');
/** @var $mailService \MailMan\Service\MailService */
$mailService = $this->getServiceLocator()->get('MailMan\Gmail');
$mailService->send($message);
```

The content of `email/example.phtml` file will be:

```php
<h2>Hi <?= $name; ?>,</h2>
This is an example email with template.
```

## Transports configuration examples

### Mandrill

To use the Mandrill transport add `"mandrill/mandrill"` to your `composer.json`.

Then configure it.

```php
'mailman' => [
    'MailMan\Mandrill' => [
        'default_sender' => 'test@mail.com',
        'transport' => [
            'type' => 'mandrill',
            'options' => [
                'api_key' => 'MYSECRETMANDRILLKEY',
                'sub_account' => 'my-optional-subaccount-if-any'
            ],
        ],
    ],
]
```

### SMTP

In this example we use the SMTP transport (shipped by ZF2).

```php
'mailman' => [
    'MailMan\SMTP' => [
        'default_sender' => 'my-name-is-methos@gmail.com',
        'transport' => [
            'type' => 'smtp',
            'options' => [
                 'host' => 'smtp.gmail.com',
                 'port' => '587',
                 'connection_class' => 'login',
                 'connection_config' => [
                     'ssl' => 'tls',
                     'username' => 'my-name-is-methos@gmail.com',
                     'password' => 'MYSECRETPASSWORD',
                 ]
             ]
        ],
    ],
],
```

---

[![Analytics](https://ga-beacon.appspot.com/UA-49655829-1/ripaclub/zf2-mailman)](https://github.com/igrigorik/ga-beacon)
