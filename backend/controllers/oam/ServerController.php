<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/11/19
 * Time: 10:19
 */

namespace app\controllers\oam;


use app\controllers\basis\CommonController;
use vendor\project\helpers\Constant;

class ServerController extends CommonController
{
    /**
     * 报文监控
     * @return string
     */
    public function actionMsg()
    {
        return $this->render('msg');
    }

    /**
     * 系统监控
     * @return string
     */
    public function actionInfo()
    {
        return $this->render('info', [
            'ls' => json_encode(Constant::linkStatus()),
            'ws' => json_encode(Constant::workStatus())
        ]);
    }
}