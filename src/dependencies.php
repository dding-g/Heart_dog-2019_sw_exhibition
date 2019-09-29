<?php

use Slim\App;

return function (App $app) {
    $container = $app->getContainer();

    // view renderer
    $container['renderer'] = function ($c) {
        $settings = $c->get('settings')['renderer'];
        return new \Slim\Views\PhpRenderer($settings['template_path']);
    };

    // monolog
    $container['logger'] = function ($c) {
        $settings = $c->get('settings')['logger'];
        $logger = new \Monolog\Logger($settings['name']);
        $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
        $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
        return $logger;
    };

    // PDO database library
    $container['db'] = function ($c) {
        $db = $c['settings']['dbSettings']['db'];
        $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'], $db['user'], $db['pass']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    };

    // -----------------------------------------------------------------------------
    // Controller factories
    // -----------------------------------------------------------------------------

    $container['App\Controller\DataController'] = function ($c) {
        $logger = $c->get('logger');
        $DBModel = $c->get('DBModel');

        return new App\Controller\DataController($c);
    };

    // -----------------------------------------------------------------------------
    // Model factories
    // -----------------------------------------------------------------------------
    
    $container['DBModel'] = function ($c) {
        $settings = $c->get('settings');
        $DBModel = new App\Model\BaseModel($c->get('db'));
        return $DBModel;
    };

};
