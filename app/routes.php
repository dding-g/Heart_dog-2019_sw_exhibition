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

$app->post('/user/profile/process', 'App\Controller\DataController:profile_process')
    ->setName('display_profile_process');

$app->post('/dog/regist/process', 'App\Controller\DoginfoController:dog_registration_process')
    ->setName('dog_registration_process');

$app->post('/dog/data_store/process', 'App\Controller\DoginfoController:dog_data_store_process')
    ->setName('store_dog_data');

$app->post('/dog/data_store/heart_rate/process', 'App\Controller\DoginfoController:heart_rate_store_process')
    ->setName('heart_rate_store_process');

$app->post('/dog/data_store/gps/process', 'App\Controller\DoginfoController:gps_store_process')
    ->setName('gps_store_process');

$app->post('/dog/select/heart_rate_history/process', 'App\Controller\DoginfoController:select_heart_rate_history_process')
    ->setName('select_heart_rate_history');

$app->post('/dog/select/process', 'App\Controller\DoginfoController:select_dog_process')
    ->setName('select_dog_process');

$app->post('/dog/delete/process', 'App\Controller\DoginfoController:delect_dog_info_process')
    ->setName('delect_dog_info_process');

$app->post('/dog/home/store/process', 'App\Controller\DoginfoController:home_device_store_dog_info')
    ->setName('home_device_store_dog_info');

$app->post('/test/response', 'App\Controller\DoginfoController:test_response')
    ->setName('test_response');

$app->post('/test/response/view', 'App\Controller\DoginfoController:test_response_view')
    ->setName('test_response_view');