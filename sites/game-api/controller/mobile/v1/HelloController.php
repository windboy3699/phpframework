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
        //autoload test

        $model = new Kernel_Game_Model_UserModel();

        $pro = new Shop_Product();

        $vip = new Video_Pay_Vip();


        $player = new PlayerModel();

        //echo $vip->name;

        //echo $model->tableName();


        $hello = $this->request->getParam('hello', '');

        $this->out['hello'] = $hello ? $hello : 'hello world';

        $this->render('mobile/v1/hello.html');
    }
}