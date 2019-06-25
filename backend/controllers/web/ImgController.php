<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/6/24
 * Time: 17:29
 */

namespace app\controllers\web;


use app\controllers\basis\CommonController;

class ImgController extends CommonController
{
    /**
     * 上传图片
     * @return string
     */
    public function actionAdd()
    {
        return $this->render('add');
    }
}