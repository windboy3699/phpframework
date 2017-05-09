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
use App\Service\SystemService;

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
     * init data
     */
    public function init()
    {
        $this->setCommonOuts();
    }

    /**
     * set common outs
     */
    private function setCommonOuts()
    {
        $systemService = new SystemService();
        $leftmenus = $systemService->getUserMenusByGroupId($_SESSION['system_group_id']);
        $this->out['leftmenus'] = $leftmenus;
        $this->out['system_realname'] = $_SESSION['system_realname'] ? $_SESSION['system_realname'] : '';
        $this->out['system_group_name'] = $_SESSION['system_group_name'] ? $_SESSION['system_group_name'] : '';
        $this->out['breadcrumbs'] = [];
        $this->out['static'] = $this->app->getConfig('static_base_url');
        $this->out['cururl'] = $this->request->getCurUrl();
        $this->out['referer'] = $_SERVER['HTTP_REFERER'];
    }
}