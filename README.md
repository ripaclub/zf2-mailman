## ZF2 Email Module


Example config node

Smtp

```php
'mail_module' => [
    'gmail' => [
        'default_sender' => 'mailmoduletest@gmail.com',
        'transport' => [
            'type' => 'smtp',
            'options' => [
                 'host' => 'smtp.gmail.com',
                 'port' => '587',
                 'connection_class' => 'login',
                 'connection_config' => [
                     'ssl'     => 'tls',
                     'username' => 'mailmoduletest@gmail.com',
                     'password' => 'MYSECRETPASSWORD',
                 ]
             ]
        ],
    ],
],
```


Mandrill

```php
'mail_module' => [
    'mandrill' => [
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