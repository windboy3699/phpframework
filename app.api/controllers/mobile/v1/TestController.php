<?php
class Mobile_V1_TestController extends AbstractController
{
    public function showAction()
    {
        $id = $this->request->getParam(['id']);
        $title = $this->spf->getRedis()->get($id);;
        $this->out['title'] = $title;
        $this->render('demo.html');
    }
}