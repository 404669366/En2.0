<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/8/23
 * Time: 16:18
 */

namespace app\controllers\field;


use app\controllers\basis\AuthController;
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
        $rule = json_decode((new client())->hGetField('PileInfo', $no, 'rules'), true);
        return $this->render('rule.html', ['rules' => $rule ?: [[0, 86400, 0.8, 0.6]]]);
    }
}