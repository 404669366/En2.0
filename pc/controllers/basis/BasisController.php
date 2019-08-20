<?php
/**
 * Created by PhpStorm.
 * User: 40466
 * Date: 2018/9/21
 * Time: 10:16
 */

namespace app\controllers\basis;


use vendor\project\helpers\Constant;
use vendor\project\helpers\Msg;
use yii\web\Controller;

class BasisController extends Controller
{
    public function beforeAction($action)
    {
        Msg::setSize();
        return parent::beforeAction($action);
    }

    public function render($view, $params = [])
    {
        if ($params['user'] = \Yii::$app->user->identity) {
            $params['user'] = [
                'id' => $params['user']->id,
                'tel' => $params['user']->tel,
                'money' => $params['user']->money ?: 0,
                'points' => $params['user']->points ?: 0
            ];
        }
        $params['friends'] = Constant::friends();
        //echo '<script>var global = JSON.parse(`' . json_encode($params, JSON_UNESCAPED_UNICODE) . '`)</script>';
        return parent::render($view, $params);
    }

    /**
     * 返回json数据
     * @param array $data
     * @param bool $type
     * @param string $msg
     * @return string
     */
    public function rJson($data = [], $type = true, $msg = 'ok')
    {
        echo json_encode(['type' => $type, 'msg' => $msg, 'data' => $data], JSON_UNESCAPED_UNICODE);
        exit();
    }

    /**
     * 返回xml数据
     * @param array $data
     * @return string
     */
    public function rXml($data = [])
    {
        $xml = '<xml>';
        foreach ($data as $k => $v) {
            $xml .= "<$k>$v</$k>";
        }
        $xml .= '</xml>';
        echo $xml;
        exit();
    }

    /**
     * 获取xml
     * @return array
     */
    public function getXml()
    {
        return (array)simplexml_load_string(file_get_contents("php://input"), 'SimpleXMLElement', LIBXML_NOCDATA);
    }

    /**
     * 重写goBack，返回上一页
     * @param string $msg
     * @return \yii\web\Response
     */
    public function goBack($msg = '')
    {
        Msg::set($msg);
        return parent::goBack(\Yii::$app->request->getReferrer());
    }

    /**
     * 自定义错误页
     * @return string
     */
    public function actionError()
    {
        return $this->render('error');
    }

    /**
     * 记录用户中心访问路由
     */
    public function rUCenterUrl()
    {
        setcookie('user-show', \Yii::$app->request->url, time() + 3600, '/');
    }
}