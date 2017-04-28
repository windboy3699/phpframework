<?php
/**
 * Created by PhpStorm.
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: GroupController.php 2017-04-28 $
 */
namespace App\Controller\System;

use App\Controller\Controller;
use App\Model\SystemGroupModel;

class GroupController extends Controller
{
    public function indexAction()
    {
        $model = new SystemGroupModel();
        $this->out['groups'] = $model->getAll();
        $this->render('system/group.html');
    }

    public function addAction()
    {
        $this->render('system/group_edit.html');
    }
}