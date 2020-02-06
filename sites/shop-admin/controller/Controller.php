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
        $leftMenus = $systemService->getUserMenusByGroupId($_SESSION['systemGroupId']);
        $this->out['leftMenus'] = $leftMenus;
        $this->out['systemRealname'] = $_SESSION['systemRealname'] ? $_SESSION['systemRealname'] : '';
        $this->out['systemGroupName'] = $_SESSION['systemGroupName'] ? $_SESSION['systemGroupName'] : '';
        $this->out['breadCrumbs'] = [];
        $this->out['static'] = $this->app->getConfig('baseStaticUrl');
        $this->out['cururl'] = $this->request->getCurUrl();
        $this->out['referer'] = $_SERVER['HTTP_REFERER'];
    }
}