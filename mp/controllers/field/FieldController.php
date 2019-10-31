<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/9/19
 * Time: 17:10
 */

namespace app\controllers\field;


use app\controllers\basis\BasisController;
use vendor\project\base\EnField;
use vendor\project\base\EnMember;
use vendor\project\helpers\Constant;

class FieldController extends BasisController
{
    /**
     * 场地列表页
     * @param int $type
     * @param string $search
     * @return string
     */
    public function actionList($type = 1, $search = '')
    {
        return $this->render('list.html', [
            'fields' => EnField::listDataByMp($type, $search)
        ]);
    }

    /**
     * 场地详情
     * @param string $no
     * @return string
     */
    public function actionDetail($no = '')
    {
        if ($detail = EnField::detailData($no)) {
            return $this->render('detail.html', ['detail' => $detail]);
        }
        return $this->redirect(['basis/basis/error']);
    }
}