## ZF2 Email Module


Example config node

```
'mail_module' => [
    'default' => [
        'sender' => 'lorenzofontana.work@gmail.com',
        'transport' => [
            'type' => 'smtp',
            'options' => [
                 'host' => 'smtp.gmail.com',
                 'port' => '587',
                 'connection_class' => 'login',
                 'connection_config' => [
                     'ssl'     => 'tls',
                     'username' => 'lorenzofontana.work@gmail.com',
                     'password' => 'MYSECRETPASSWORD',
                 ]
             ]
        ],
    ],
],
```