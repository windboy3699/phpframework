<?php
/**
 * Created by PhpStorm.
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: UpdateCacheJob.php 2017-04-21 $
 */
namespace Shop\Admin\Job;

use SPF\SPF;
use Shop\Admin\Job\AbstractJob;

class UpdateCacheJob extends AbstractJob
{
    public function getOptArgs()
    {
        return array(
            'id:',
            'name:',
        );
    }

    public function run()
    {
        echo $this->getCommendArg('id') . "\n";
        echo $this->getCommendArg('name') . "\n";

        $redis = SPF::app()->getRedis();
        $redis->set('test', 'what are you donging?');
        echo $redis->get('test') . "\n";exit;
    }
}