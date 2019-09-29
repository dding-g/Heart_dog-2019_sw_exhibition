<?php
    $container = $app->getContainer();

    // // view renderer
    // $container['renderer'] = function ($c) {
    //     $settings = $c->get('settings')['renderer'];
    //     return new \Slim\Views\PhpRenderer($settings['template_path']);
    // };

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
        $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'], $db['user'], $db['pass'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    };

    // email setting
    $container['email'] = function ($c) {
        $emailconfig = array(
                            'host' => $c['settings']['emailSetting']['host'],
                            'password' => $c['settings']['emailSetting']['password'],
                            'port' =>  $c['settings']['emailSetting']['port'],
                            );
        return $emailconfig;
    };


    // -----------------------------------------------------------------------------
    // Controller factories
    // -----------------------------------------------------------------------------
    
    $container['app\controllers\DataController'] = function ($c) {
        return new App\Controller\DataController($c);
    };

    $container['app\models\DataModel'] = function ($c) {
        return new App\Model\DataModel($c);
    };

    // -----------------------------------------------------------------------------
    // Model factories
    // -----------------------------------------------------------------------------
    
    $container['DBModel'] = function ($c) {
        return new App\Model\DataModel($c);
    };

    $container['email_config'] = function ($c) {
        return new App\Model\EmailModel($c->get('email'));
    };



