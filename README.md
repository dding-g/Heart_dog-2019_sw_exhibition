
## Heart_dog-2019_sw_exhibition

# 1. 개발 내용

* `심장박동 센서, GPS , 블루투스 , 무선 통신 모듈이 아두이노 나노와 연결되어 구성됩니다.
안드로이드 어플리케이션을 통해 아두이노 나노와 블루투스 통신으로 데이터를 주고 받으며,
만약, 아두이노 나노와 안드로이드 간 블루투스 연결이 제한될 경우에는 무선 통신 모듈을
사용하여 집에 있는 다른 아두이노 나노와 통신해 데이터를 서버로 전송합니다.
또한 DB 에 저장된 위도와 경도, 심장박동 데이터를 Restful API를 이용해
스마트폰 어플리케이션에서 실시간으로 반려동물의 위치와 심장박동을 확인할 수 있습니다' 

# 2. 개발 환경

# 아누이노 NANO
*        통신 모듈 : NRF24L01
*        블루투스 모듈 : HC-05 
*        GPS 모듈 : NEO-7M
*        심박 센서
*        Wemos D1 MINI PRO (for. ESP8266)
*        ArduinoJson : https://github.com/bblanchon/ArduinoJson
       
# 안드로이드 : Java
*        Google Map ( 아두이노에서 받은 GPS 위치 기반으로 위치 표시 )
*        User Management ( 회원가입, 로그인 )
*        Dog Management ( 강아지 등록 )
*        Open Weather Map API : https://openweathermap.org/api
*        Google Map API : https://cloud.google.com/maps-platform/?hl=ko
*        MPAndroidChart : https://github.com/PhilJay/MPAndroidChart

       

# 서버 : PHP, Slim Framework
*       Data Transfer ( 아두이노 / 안드로이드에서 받은 데이터 기반으로 저장 및 Transfer )
*       DB Communication
*       User Management ( 회원가입, 로그인 )
*       Dog Management ( 강아지 등록 )
*       Slim3 Framework : http://www.slimframework.com/
       

# DB : MySQL
*       Data Store

=============================================================================================  

# Slim 3 MVC Skeleton

This is a simple skeleton project for Slim 3 that includes FluentPDO, Twig and Monolog.

Base on https://github.com/vhchung/slim3-skeleton-mvc

## Prepare

1. Create your project:

       `$ composer create-project -n -s dev mrmoneyc/slim-mvc-skeleton YOUR_APP_NAME`

2. Create database: `$ cat sql/db.sql | sqlite3 storage/db/db.sqlite`

### Run it:

1. `$ cd YOUR_APP_NAME`
2. `$ php -S 0.0.0.0:8888 -t public/`
3. Browse to http://localhost:8888

### Run coding style check

1. Install [PHP_CodeSniffer] (https://github.com/squizlabs/PHP_CodeSniffer)
2. `$ cd YOUR_APP_NAME`
3. `$ phpcs`

### Run test

1. Install [PHPUnit] (https://phpunit.de/)
2. `$ cd YOUR_APP_NAME`
3. `$ phpunit`

### Notice

Set `storage/` folder permission to writable when deploy to production environment

## Key directories

* `app`: Application code
* `app/controllers`: Controller files
* `app/models`: Model files
* `app/templates`: Template files
* `storage/log`: Log files
* `storage/db`: SQLite DB files
* `public`: Webserver root
* `vendor`: Composer dependencies
* `sql`: sql dump file for sample database

## Key files

* `public/index.php`: Entry point to application
* `app/settings.php`: Configuration
* `app/dependencies.php`: Services for Pimple
* `app/middleware.php`: Application middleware
* `app/routes.php`: All application routes are here
* `app/controllers/IndexController.php`: Controller class for the home page
* `app/models/ConfigurationModel.php`: Model class for configurations table
* `app/templates/index.twig`: Template file for the home page
============
