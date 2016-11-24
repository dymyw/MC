<?php
/**
 * Default controller
 *
 * @package App_Controller
 * @author Dymyw <dymayongwei@163.com>
 * @since 2014-10-10
 * @version 2016-11-24
 */

namespace App\Controller;

use Core\Utils\Http;
use Core\View\Model\ViewModel;
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

        // test model
//        $data = $this->models->test->getTest('dymyw');
//        return $data;

        // func saveLog
//        $this->func->saveLog(LOG_DIR . 'temp.log', var_export([
//            'gender' => 'men',
//            'page' => 2,
//        ], true));

        // core url helper
//        echo $this->helpers->url('eyeglasses/gender', [
//            'gender' => 'men',
//            'page' => 2,
//        ], false, true);


        // min helper
//        return [
//            'data' => $this->helpers->min(12, 36),
//        ];

        // json model
//        return $jsonModel = JsonModel::init([
//            'name' => 'dymyw',
//            'lang' => 'php',
//        ])->setJsonpCallback('console.log');

        // core plugin viewModel
//        var_dump($this->viewModel(['EyeglassesController', 'filterAction']));

        /* @var $redis \Core\Cache\Redis */
//        try {
//            $redis = $this->locator->get('Core\Cache\Redis');
////            var_dump($redis->get('tttt', function() {
////                return 'dymyw';
////            }, 10));
//            var_dump($redis->get('tttt'));
////            $redis->close();
//        } catch (\RedisException $e) {
//            echo $e->getMessage();
//        }
//        exit;
    }

    public function ajaxResponseAction()
    {
        $id = $this->param('id');
        $name = $this->param('name');

        // check and set error
        $error = '';
        if (!$name) {
            $error = "Name should not empty!";
        }

        $data = [
            'id' => $id,
            'name' => $name,
        ];

        if (Http::isAjax()) {
            $viewModel = new ViewModel($data, 'default/ajax-response');
            if ($error) {
                return JsonModel::init('error', $error);
            } else {
                return JsonModel::init('succ', '', $this->view->render($viewModel));
            }
        } else {
            var_dump($data);

            return false;
        }
    }

    public function notFoundAction()
    {
//        var_dump('default - not found');
    }
}
