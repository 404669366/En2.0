<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/6/24
 * Time: 14:30
 */

namespace app\controllers\field;


use app\controllers\basis\BasisController;
use vendor\project\base\EnField;
use vendor\project\helpers\Constant;
use vendor\project\helpers\Helper;

class FieldController extends BasisController
{
    /**
     * 项目列表
     * @return string
     */
    public function actionList()
    {
        return $this->render('list.html', [
            'data' => EnField::listDataByPc(),
        ]);
    }

    /**
     * 项目详情
     * @param string $no
     * @return string|\yii\web\Response
     */
    public function actionDetail($no = '')
    {
        if ($detail = EnField::detailData($no)) {
            return $this->render('detail.html', ['detail' => $detail,]);
        }
        return $this->redirect(['basis/basis/error']);
    }
}