<?php
/**
 * Test controller
 *
 * @package App_Controller
 * @author Dymyw <dymayongwei@163.com>
 * @since 2014-10-09
 * @version 2016-10-18
 */

namespace App\Controller;

class EyeglassesController extends AbstractActionController
{
    public function init()
    {
        parent::init();
        var_dump('eyeglasses - init');
    }

    public function indexAction()
    {
        var_dump('eyeglasses - index');
    }

    public function genderAction()
    {
        var_dump('eyeglasses - gender', $_GET);
    }

    public function listAction()
    {
        var_dump('eyeglasses - list', $_GET);
        var_dump($this->locator->controllerName . '-' . $this->locator->actionName);
    }

    public function notFoundAction()
    {
        var_dump('eyeglasses - not found');
    }
}
