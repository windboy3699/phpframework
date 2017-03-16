<?php
/**
 * Created by PhpStorm.
 * @package /
 * @author  XiaodongPan
 * @version $Id: HelloController.php 2017-03-16 $
 */
class Mobile_V1_HelloController extends AbstractController
{
    public function showAction()
    {
        $model = new Kernel_Game_Model_UserModel();

        $pro = new Shop_Product();

        echo $pro->name;

        echo $model->tableName();

        exit;

        $this->render('demo.html');
    }
}