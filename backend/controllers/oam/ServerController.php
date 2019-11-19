<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/11/19
 * Time: 10:19
 */

namespace app\controllers\oam;


use app\controllers\basis\CommonController;

class ServerController extends CommonController
{
    /**
     * 服务器消息
     * @return string
     */
    public function actionMsg()
    {
        return $this->render('msg');
    }
}