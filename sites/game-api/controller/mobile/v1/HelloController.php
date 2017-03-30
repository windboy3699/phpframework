<?php
/**
 * Created by PhpStorm.
 * @package /
 * @author  XiaodongPan
 * @version $Id: HelloController.php 2017-03-16 $
 */
class Mobile_V1_HelloController extends AbstractController
{
    public function viewAction()
    {
        echo 'view';
    }
    
    public function showAction()
    {
        //test autoload
        $model = new Kernel_Game_Model_UserModel();
        //echo $model->tableName();exit;

        //Kernel_Game_Model_UserModel::say();exit;

        $pro = new Shop_Product();

        $vip = new Video_Pay_Vip();

        $player = new PlayerModel();

        //echo $player->name;exit;

        $hello = $this->request->getParam('hello', '');

        $this->out['hello'] = $hello ? $hello : 'hello world';

        $this->render('mobile/v1/hello.html');
    }
}