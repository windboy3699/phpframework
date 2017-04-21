<?php
/**
 * Created by PhpStorm.
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: UpdateJob.php 2017-04-21 $
 */
namespace App\Category;

use App\AbstractJob;

class UpdateJob extends AbstractJob
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
        echo "hello job\n";
    }
}