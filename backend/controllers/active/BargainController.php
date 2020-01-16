<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2020/01/16
 * Time: 16:48
 */

namespace app\controllers\active;


use app\controllers\basis\CommonController;
use vendor\project\base\EnBargain;
use vendor\project\base\EnBargainRecord;

class BargainController extends CommonController
{
    /**
     * 砍价
     * @return string
     */
    public function actionList()
    {
        return $this->render('list');
    }

    /**
     * 砍价数据
     * @return string
     */
    public function actionData()
    {
        return $this->rTableData(EnBargain::getPageData());
    }

    /**
     * 砍价记录
     * @param int $id
     * @return string
     */
    public function actionRecord($id = 0)
    {
        return $this->render('record', ['id' => $id]);
    }

    /**
     * 砍价记录数据
     * @param int $id
     * @return string
     */
    public function actionRecordData($id = 0)
    {
        $data = EnBargainRecord::getRecord($id);
        return $this->rTableData(['data' => $data, 'total' => count($data)]);
    }
}