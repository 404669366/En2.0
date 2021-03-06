<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2018/10/19
 * Time: 13:49
 */

namespace app\controllers\basis;


use vendor\project\helpers\Oss;

class FileController extends AuthController
{
    public $enableCsrfValidation = false;

    /**
     * 上传文件
     * @return string
     */
    public function actionUpload()
    {
        if (\Yii::$app->request->isPost) {
            if ($_FILES) {
                if ($re = Oss::aliUpload($_FILES['file'])) {
                    return $this->rJson($re);
                }
            }
            return $this->rJson('', false, '上传错误');
        }
        return $this->rJson('', false, '请求错误');
    }

    /**
     * 删除文件
     * @param $name
     * @return string
     */
    public function actionDelete($name)
    {
        if ($name && Oss::aliDelete($name)) {
            return $this->rJson();
        }
        return $this->rJson('', false, '删除错误');
    }
}