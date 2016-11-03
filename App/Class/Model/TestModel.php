<?php
/**
 * Test model
 *
 * @package App_Model
 * @author Dymyw <dymayongwei@163.com>
 * @since 2015-01-19
 * @version 2016-11-03
 */

namespace App\Model;

use Core\Model\AbstractModel;

class TestModel extends AbstractModel
{
    public function getTest($abc)
    {
//        var_dump($this->db);
//        var_dump($this->models);

        return [
            'data' => $abc . ' - Test Model',
        ];
    }
}
