<?php
// Routes
$app->get('/post/{id}', 'App\Controller\HomeController:viewPost')
    ->setName('view_post');

$app->get('/', 'App\Controller\DataController:db_test')
    ->setName('db_test');

$app->get('/test', 'App\Controller\DataController:db_test')
    ->setName('db_test');