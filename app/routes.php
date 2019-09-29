<?php
// Routes
$app->get('/post/{id}', 'App\Controller\HomeController:viewPost')
    ->setName('view_post');

$app->get('/', 'App\Controller\IndexController:index')
    ->setName('home');

$app->get('/test', 'App\Controller\DataController:db_test')
    ->setName('db_test');

$app->post('/user/sign_up/process', 'App\Controller\DataController:sign_up_process')
    ->setName('sign_up_process');

$app->post('/user/sign_up/authorizing', 'App\Controller\DataController:check_authorized_code')
    ->setName('check_authorized_code');

$app->post('/user/sign_in/process', 'App\Controller\DataController:sign_in_process')
    ->setName('check_authorized_code');