<?php

// set database parameters based on server
if ($_SERVER['HTTP_HOST'] == 'caerang2.esllee.com') {
    $db_array = array(
        'host' => '127.0.0.1',
        'user' => 'caerang_user_02',
        'pass' => '1q2w3e4r!@#',
        'dbname' => 'caerang_user_02_schema'
    );
} else {
    $db_array = array(
        'host' => '127.0.0.1',
        'user' => 'caerang_user_02',
        'pass' => '1q2w3e4r!@#',
        'dbname' => 'caerang_user_02_schema'
    );
}


return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

         // Database connection settings
         'dbSettings' => array(
            'db' => $db_array,
        ),

        'emailSetting' => array(
            'host'=> 'area409@gmail.com',
            'password' => 'persona333',
            'port' => 587,
        ),

    ],
];
