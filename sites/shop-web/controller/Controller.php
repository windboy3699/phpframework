<?php
/**
 * Created by PhpStorm.
 *
 * @package App.Controller
 * @author  XiaodongPan
 * @version $Id: Controller.php 2017-04-19 $
 */
namespace App\Controller;

use SPF\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    /**
     * Controller constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 执行请求
     * @return mixed
     */
    abstract public function handleRequest();
}