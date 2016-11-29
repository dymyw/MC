<?php

namespace App\Controller;

use Core\Utils\Http;

class DefaultController extends AbstractActionController
{
    public function init()
    {
        parent::init();
    }

    public function notFoundAction()
    {
        Http::headerStatus(404);
        $this->layout(false);
    }

    public function indexAction()
    {
        echo '新项目';
    }
}
