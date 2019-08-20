<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/6/24
 * Time: 14:29
 */

namespace app\controllers\about;


use app\controllers\basis\BasisController;

class AboutController extends BasisController
{
    /**
     * 公司介绍
     * @return string
     */
    public function actionCompany()
    {
        return $this->render('company.html');
    }

    /**
     * 合作伙伴
     * @return string
     */
    public function actionPartner()
    {
        return $this->render('partner.html');
    }

    /**
     * 联系我们
     * @return string
     */
    public function actionContact()
    {
        return $this->render('contact.html');
    }

    /**
     * 用户指南
     * @return string
     */
    public function actionGuide()
    {
        return $this->render('guide.html');
    }
}