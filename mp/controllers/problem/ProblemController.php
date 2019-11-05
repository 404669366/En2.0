<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/8/21
 * Time: 12:42
 */

namespace app\controllers\problem;


use app\controllers\basis\AuthController;
use vendor\project\helpers\Constant;

class ProblemController extends AuthController
{
    /**
     * 常见问题
     * @return string
     */
    public function actionProblem()
    {
        return $this->render('problem.html', ['sysTel' => Constant::serviceTel()]);
    }
}