<?php
/**
 * AbstractActionController
 *
 * @package App_Controller
 * @author Dymyw <dymayongwei@163.com>
 * @since 2015-01-05
 * @version 2016-10-24
 */

namespace App\Controller;

use Core\Controller\AbstractActionController as ActionController;

abstract class AbstractActionController extends ActionController
{
    public function init()
    {
        // set default layout
        $this->layout();
    }
}
