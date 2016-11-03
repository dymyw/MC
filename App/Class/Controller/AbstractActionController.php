<?php
/**
 * AbstractActionController
 *
 * @package App_Controller
 * @author Dymyw <dymayongwei@163.com>
 * @since 2015-01-05
 * @version 2016-11-03
 */

namespace App\Controller;

use Core\Controller\AbstractActionController as ActionController;
use Core\View\Model\ViewModel;

abstract class AbstractActionController extends ActionController
{
    public function init()
    {
        // set default layout
        $this->layout();
        $this->layout
                ->addChild(new ViewModel('layout/includes/header'), '__header')
                ->addChild(new ViewModel('layout/includes/sidebar'), '__sidebar')
                ->addChild(new ViewModel('layout/includes/footer'), '__footer');

        // register plugin to helper
        $this->helpers->register('max', function() {
            return $this->plugin('max');
        });
    }
}
