# ZF2MailModule
[![Latest Stable Version](https://poser.pugx.org/fntlnz/zf2-mail-module/v/stable.svg)](https://packagist.org/packages/fntlnz/zf2-mail-module)[![Latest Unstable Version](https://poser.pugx.org/fntlnz/zf2-mail-module/v/unstable.svg)](https://packagist.org/packages/fntlnz/zf2-mail-module) [![License](https://poser.pugx.org/fntlnz/zf2-mail-module/license.svg)](https://packagist.org/packages/fntlnz/zf2-mail-module)

## What is this?
This is a Zf2 Module that gives you a simple way to configure one or multiple Mail Services.

It supports [all transports](https://github.com/zendframework/zf2/tree/master/library/Zend/Mail/Transport) shipped with ZF2
so for instance any transport that implements the `Zend\Mail\Transport\TransportInterface`.

It also has a transport for [Mandrill](http://mandrill.com)

## Installation

Add `fntlnz/zf2-mail-module` to your composer.json
```
{
   "require": {
       "fntlnz/zf2-mail-module": "v0.1.0"
   }
}
```

## Usage

Configure a transport in your configuration file


```php
'mail_module' => [
    'MailModule\Gmail' => [
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
/** @var \MailModule\Service\MailService $mailService */
$mailService = $this->getServiceLocator()->get('MailModule\Gmail');
$mailService->setBody('Test email');
$mailService->getMessage()->setSubject('My name is methos');
$mailService->getMessage()->addFrom('my-name-is-methos@gmail.com', 'Methos');
$mailService->getMessage()->addTo('fontanalorenz@gmail.com', 'Lorenzo');
$mailService->send();
```

**Message with attachment**

```php
/** @var \MailModule\Service\MailService $mailService */
$mailService = $this->getServiceLocator()->get('MailModule\Gmail');
$mailService->addAttachment('/path/to/an/attachment.png');
$mailService->setBody('Test email');
$mailService->getMessage()->setSubject('My name is methos');
$mailService->getMessage()->addFrom('my-name-is-methos@gmail.com', 'Methos');
$mailService->getMessage()->addTo('fontanalorenz@gmail.com', 'Lorenzo');
$mailService->send();
```

**Message using a template**

```php
/** @var $mailService \MailModule\Service\MailService */
$mailService = $this->getServiceLocator()->get('MailModule\Gmail');
$content = new ViewModel();
$content->setTemplate('email/example.phtml');
$content->setVariable('name', 'Lorenzo');
$mailService->getMessage()->setSubject('Example email');
$mailService->setBody($this->getServiceLocator()->get('ViewRenderer')->render($content));
$mailService->getMessage()->addTo('fontanalorenz@gmail.com', 'Lorenzo');
$mailService->send();
```

email/example.phtml
```php
<h2>Hi <?=$name;?>,</h2>
This is an example email with template.
```

## Transports configuration examples

### Mandrill
To use the Mandrill transport add  `"mandrill/mandrill"` to your composer.json
```php
'mail_module' => [
    'MailModule\Mandrill' => [
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

```php
'mail_module' => [
    'MailModule\SMTP' => [
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


## License


