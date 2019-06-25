<?php
/**
 * Created by PhpStorm.
 * User: QAQ宇酱
 * Date: 2018/5/13
 * Time: 14:24
 */

namespace vendor\project\helpers;


class CaptchaCode
{
    private $charset;//随机因子
    private $code;//验证码
    private $codelen = 4;//验证码长度
    private $width = 130;//宽度
    private $height = 50;//高度
    private $img;//图形资源句柄
    private $font;//指定的字体
    private $fontsize = 20;//指定字体大小
    private $fontcolor;//指定字体颜色

    /**
     * 构造方法初始化
     * CaptchaCode constructor.
     * @param string $charset
     * QAQ宇酱
     */
    public function __construct($charset = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $this->charset = $charset;
        $this->font = dirname(__FILE__) . '/elephant.ttf';//注意字体路径要写对，否则显示不了图片
    }

    /**
     * 生成随机码
     * QAQ宇酱
     */
    private function createCode()
    {
        $_len = strlen($this->charset) - 1;
        for ($i = 0; $i < $this->codelen; $i++) {
            $this->code .= $this->charset[mt_rand(0, $_len)];
        }
    }

    /**
     * 生成背景
     * QAQ宇酱
     */
    private function createBg()
    {
        $this->img = imagecreatetruecolor($this->width, $this->height);
        $color = imagecolorallocate($this->img, mt_rand(157, 255), mt_rand(157, 255), mt_rand(157, 255));
        imagefilledrectangle($this->img, 0, $this->height, $this->width, 0, $color);
    }

    /**
     * 生成文字
     * QAQ宇酱
     */
    private function createFont()
    {
        $_x = $this->width / $this->codelen;
        for ($i = 0; $i < $this->codelen; $i++) {
            $this->fontcolor = imagecolorallocate($this->img, mt_rand(0, 156), mt_rand(0, 156), mt_rand(0, 156));
            imagettftext($this->img, $this->fontsize, mt_rand(-30, 30), $_x * $i + mt_rand(1, 5), $this->height / 1.4, $this->fontcolor, $this->font, $this->code[$i]);
        }
    }

    /**
     * 生成线条、雪花
     * QAQ宇酱
     */
    private function createLine()
    {
        //线条
        for ($i = 0; $i < 6; $i++) {
            $color = imagecolorallocate($this->img, mt_rand(0, 156), mt_rand(0, 156), mt_rand(0, 156));
            imageline($this->img, mt_rand(0, $this->width), mt_rand(0, $this->height), mt_rand(0, $this->width), mt_rand(0, $this->height), $color);
        }
        //雪花
        for ($i = 0; $i < 100; $i++) {
            $color = imagecolorallocate($this->img, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255));
            imagestring($this->img, mt_rand(1, 5), mt_rand(0, $this->width), mt_rand(0, $this->height), '*', $color);
        }
    }

    /**
     * 输出
     * QAQ宇酱
     */
    private function outPut()
    {
        header('Content-type:image/png');
        imagepng($this->img);
        imagedestroy($this->img);
    }

    /**
     * 保存当前验证码
     * @param $model
     * QAQ宇酱
     */
    private function setCode($model)
    {
        \Yii::$app->session->set($model, strtolower($this->code));
    }

    /**
     * 对外生成图片验证码
     * @param string $model
     * QAQ宇酱
     */
    public function doimg($model = '')
    {
        $this->createBg();
        $this->createCode();
        $this->createLine();
        $this->createFont();
        $this->outPut();
        if ($model) {
            $this->setCode($model);
        }
    }

    /**
     * 验证验证码
     * @param string $code
     * @param string $model
     * @return bool
     * QAQ宇酱
     */
    public static function validate($code = '', $model = '')
    {
        if ($code && $model) {
            $old = \Yii::$app->session->get($model);
            if ($old == strtolower($code)) {
                return true;
            }
        }
        return false;
    }
}