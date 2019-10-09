<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/8/23
 * Time: 16:18
 */

namespace app\controllers\field;


use app\controllers\basis\AuthController;
use vendor\project\base\EnPile;
use vendor\project\helpers\client;

class PileController extends AuthController
{
    /**
     * 查询计费规则
     * @param string $no
     * @return string
     */
    public function actionRule($no = '')
    {
        return $this->render('rule.html', ['rules' => json_decode(EnPile::findOne(['no' => $no])->rules, true)]);
    }
}