<?php

namespace Api\Post;

use Api\BaseController;
use Api\Response;
use PDO;
use Api\Database;


class PostController extends BaseController
{
    public $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
}
