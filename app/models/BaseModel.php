<?php
namespace App\Model;

use Slim\Container;

class BaseModel
{
    public function __construct(Container $c)
    {
        $this->db = $c->get('db');
    }
}
