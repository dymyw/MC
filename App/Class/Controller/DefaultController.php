<?php
/**
 * Default controller
 *
 * @package App_Controller
 * @author Dymyw <dymayongwei@163.com>
 * @since 2014-10-10
 * @version 2016-10-21
 */

namespace App\Controller;

use Core\View\Model\JsonModel;

class DefaultController extends AbstractActionController
{
    public function init()
    {
        parent::init();
//        var_dump('default - init');
    }

    public function indexAction()
    {
//        var_dump('default - index');

        // app plugin max
//        var_dump($this->max(6, 8));

        // app plugin func
//        var_dump($this->func);
//        var_dump($this->func->getSum(6, 6));

        // view model
//        return [
//            'data' => 'default - index',
//        ];

        // json model
        return $jsonModel = JsonModel::init([
            'name' => 'dymyw',
            'lang' => 'php',
        ])->setJsonpCallback('console.log');
    }

    public function notFoundAction()
    {
//        var_dump('default - not found');
    }
}
