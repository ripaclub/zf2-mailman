# ZF2 Mail Manager
[![Build Status](https://travis-ci.org/ripaclub/zf2-mailman.svg?branch=develop)](https://travis-ci.org/ripaclub/zf2-mailman) [![Latest Stable Version](https://poser.pugx.org/ripaclub/zf2-mailman/v/stable.svg)](https://packagist.org/packages/ripaclub/zf2-mailman) [![Latest Unstable Version](https://poser.pugx.org/ripaclub/zf2-mailman/v/unstable.svg)](https://packagist.org/packages/ripaclub/zf2-mailman) [![License](https://poser.pugx.org/ripaclub/zf2-mailman/license.svg)](https://packagist.org/packages/ripaclub/zf2-mailman)

## What is this?
This is a Zf2 Module that gives you a simple way to configure one or multiple Mail Services.

It supports [all transports](https://github.com/zendframework/zf2/tree/master/library/Zend/Mail/Transport) shipped with ZF2, e.g. any transport that implements the `Zend\Mail\Transport\TransportInterface`.

It also has a transport for [Mandrill](http://mandrill.com). If you wish to use it install also `mandrill/mandrill` (versions 1.0.*) library.

## Installation

Add `ripaclub/zf2-mailman` to your `composer.json`.

```
{
   "require": {
       "ripaclub/zf2-mailman": "v0.1.0"
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
                     'ssl'     => 'tls',
                     'username' => 'my-name-is-methos@gmail.com',
                     'password' => 'MYSECRETPASSWORD',
                 ]
             ]
        ],
    ],
],
```

**Text only message**

```php
/** @var \MailMan\Service\MailService $mailService */
$mailService = $this->getServiceLocator()->get('MailMan\Gmail');
$mailService->setBody('Test email');
$mailService->getMessage()->setSubject('My name is methos');
$mailService->getMessage()->addFrom('my-name-is-methos@gmail.com', 'Methos');
$mailService->getMessage()->addTo('fontanalorenz@gmail.com', 'Lorenzo');
$mailService->send();
```

**Message with attachment**

```php
/** @var \MailMan\Service\MailService $mailService */
$mailService = $this->getServiceLocator()->get('MailMan\Gmail');
$mailService->addAttachment('/path/to/an/attachment.png');
$mailService->setBody('Test email');
$mailService->getMessage()->setSubject('My name is methos');
$mailService->getMessage()->addFrom('my-name-is-methos@gmail.com', 'Methos');
$mailService->getMessage()->addTo('fontanalorenz@gmail.com', 'Lorenzo');
$mailService->send();
```

**Message using a template**

```php
/** @var $mailService \MailMan\Service\MailService */
$mailService = $this->getServiceLocator()->get('MailMan\Gmail');
$content = new ViewModel();
$content->setTemplate('email/example.phtml');
$content->setVariable('name', 'Lorenzo');
$mailService->getMessage()->setSubject('Example email');
$mailService->setBody($this->getServiceLocator()->get('ViewRenderer')->render($content));
$mailService->getMessage()->addTo('fontanalorenz@gmail.com', 'Lorenzo');
$mailService->send();
```

The content of `email/example.phtml` file will be:

```php
<h2>Hi <?=$name;?>,</h2>
This is an example email with template.
```

## Transports configuration examples

### Mandrill

To use the Mandrill transport add  `"mandrill/mandrill"` to your `composer.json`.

```php
'mailman' => [
    'MailMan\Mandrill' => [
        'default_sender' => 'test@mail.com',
        'transport' => [
            'type' => 'mandrill',
            'options' => [
                'apikey' => 'MYSECRETMANDRILLKEY',
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
                     'ssl'     => 'tls',
                     'username' => 'my-name-is-methos@gmail.com',
                     'password' => 'MYSECRETPASSWORD',
                 ]
             ]
        ],
    ],
],
```
