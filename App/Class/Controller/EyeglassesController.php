<?php
/**
 * Test controller
 *
 * @package App_Controller
 * @author Dymyw <dymayongwei@163.com>
 * @since 2014-10-09
 * @version 2016-11-17
 */

namespace App\Controller;

class EyeglassesController extends AbstractActionController
{
    public function init()
    {
        parent::init();
//        var_dump('eyeglasses - init');
    }

    public function indexAction()
    {
        var_dump('eyeglasses - index');
    }

    public function genderAction()
    {
//        var_dump('eyeglasses - gender', $_GET);
//        var_dump($this->param('gender'));
    }

    public function listAction()
    {
//        var_dump('eyeglasses - list', $_GET);
//        var_dump($this->locator->controllerName . '-' . $this->locator->actionName);

        // core plugin viewModel
//        var_dump($this->viewModel('filterAction', 'dymyw'));
        return $this->viewModel([$this, 'genderAction']);
    }

    public function filterAction($str = 'nihao')
    {
        return [
            'method' => 'filter - ' . $str,
        ];
    }

    public function notFoundAction()
    {
        var_dump('eyeglasses - not found');
    }
}
