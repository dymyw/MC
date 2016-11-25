<?php
/**
 * Test controller
 *
 * @package App_Controller
 * @author Dymyw <dymayongwei@163.com>
 * @since 2014-10-09
 * @version 2016-11-25
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

        // core helper selfurl
//        echo $this->helpers->selfUrl('type=test');
        echo $this->helpers->selfUrl(['id' => 2, 'type' => 'test', 'gender' => 'men']);
        var_dump($this->param('gender'));
        var_dump($this->locator->params);
        var_dump($_GET);
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

    public function cliAction()
    {
        var_dump([
            'name' => $this->param('name'),
        ]);
    }

    public function notFoundAction()
    {
        var_dump('eyeglasses - not found');
    }
}
