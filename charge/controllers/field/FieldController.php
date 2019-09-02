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
     * 电桩详情
     * @param string $no
     * @param string $guns
     * @return string|\yii\web\Response
     */
    public function actionInfo($no = '', $guns = '')
    {
        if ($field = EnField::getFieldInfo($no)) {
            $piles = EnPile::getPilesByField($no);
            $guns = explode(',', $guns);
            $field['guns'] = ['count' => $guns[0], 'used' => $guns[1]];
            return $this->render('info.html', [
                'jsApi' => Wechat::getJsApiParams(),
                'field' => $field,
                'piles' => $piles
            ]);
        }
        return $this->goBack('充电站不见啦!');
    }
}