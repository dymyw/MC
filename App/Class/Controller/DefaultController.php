<?php
/**
 * Default controller
 *
 * @package App_Controller
 * @author Dymyw <dymayongwei@163.com>
 * @since 2014-10-10
 * @version 2016-10-18
 */

namespace App\Controller;

class DefaultController extends AbstractActionController
{
    public function init()
    {
        parent::init();
        var_dump('default - init');
    }

    public function indexAction()
    {
        var_dump('default - index');
    }

    public function notFoundAction()
    {
        var_dump('default - not found');
    }
}
