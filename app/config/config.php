<?php
return [
    'production' => false,
    'databases' => [
        'mysql' => [
            'host' => 'localhost',
            'port' => 3306,
            'name' => 'url-shortener',
            'username' => 'root',
            'password' => ''
        ]
    ],
    'core' => [
        'hostname' => 'localhost',
        'path' => '',
        'force_https' => false,
        'force_www' => false,
        'captcha' => false,
        'date_format' => 'd M Y, H:i'
    ]
];
