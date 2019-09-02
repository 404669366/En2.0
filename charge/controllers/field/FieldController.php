<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/8/21
 * Time: 9:23
 */

namespace app\controllers\field;


use app\controllers\basis\AuthController;
use vendor\project\base\EnField;
use vendor\project\base\EnPile;
use vendor\project\helpers\Wechat;

class FieldController extends AuthController
{
    /**
     * 地图发现
     * @return string
     */
    public function actionMap()
    {
        return $this->render('map.html', ['jsApi' => Wechat::getJsApiParams()]);
    }

    /**
     * 查询电桩枪口信息
     * @param $no
     * @return string
     */
    public function actionGuns($no)
    {
        return $this->rJson(EnField::getGuns($no));
    }

    /**
     * 电桩详情
     * @param string $no
     * @return string|\yii\web\Response
     */
    public function actionInfo($no = '')
    {
        if ($field = EnField::getFieldInfo($no)) {
            $piles = EnPile::getPilesByField($no);
            return $this->render('info.html', [
                'jsApi' => Wechat::getJsApiParams(),
                'field' => $field,
                'piles' => $piles
            ]);
        }
        return $this->goBack('充电站不见啦!');
    }
}