<?php
/**
 * Created by PhpStorm.
 * @package /Class
 * @author  XiaodongPan
 * @version $Id: AbstractController.php 2016-10-25 $
 */
abstract class AbstractController extends SPF_Controller
{
    private $view = null;

    /**
     * 获取模板引擎
     * @return Twig_Environment
     */
    protected function getView()
    {
        if ($this->view === null) {
            $this->view = SPF_View::create(APP_PATH . '/views/');
        }
        return $this->view;
    }

    /**
     * 显示视图
     * 默认调用 /views/group_name/controller_name/action_name.html
     * group存在才拼接
     * @param string $tpl
     */
    protected function render($tpl = '')
    {
        if (!$tpl) {
            $group = $this->spf->getGroup();
            $tpl = $group ? strtolower($group) . '/' : '';
            $tpl .= strtolower($this->spf->getController()) . '/';
            $tpl .= $this->spf->getAction() . '.html';
        }
        echo $this->getView()->render($tpl, $this->out);
    }
}