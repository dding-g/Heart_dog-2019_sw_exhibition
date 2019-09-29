<?php
namespace App\Controller;

use Slim\Container;


class BaseController
{

    public function __construct(Container $c)
    {
        $this->emailconfig = $c->get('email_config');
        $this->db_model = $c->get('DBModel');
    }
}
