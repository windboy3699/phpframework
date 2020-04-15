<?php
/**
 * Created by PhpStorm.
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: IndexController.php 2017-04-24 $
 */
namespace shop\admin;

class IndexController extends Controller
{
    public function indexAction()
    {
        $this->render('index.html');
    }
}