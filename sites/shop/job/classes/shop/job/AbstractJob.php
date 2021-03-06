<?php
/**
 * Created by PhpStorm.
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: AbstractJob.php 2017-04-21 $
 */
namespace shop\job;

use spf\SPF;

abstract class AbstractJob
{
    protected $app;

    protected $commendArgs = array();

    abstract public function run();

    /**
     * 规范使用长参数模式例如 --start=1 --end=2
     * @return array
     * array('start:','end:');
     */
    abstract function getOptArgs();

    public function __construct()
    {
        $this->app = SPF::app();
    }

    public function getCommendArgs()
    {
        if (!empty($this->commendArgs)) {
            return $this->commendArgs;
        }
        $optArgs = $this->getOptArgs();
        if (!empty($optArgs)) {
            $this->commendArgs = getopt('', $optArgs);
        }
        return $this->commendArgs;
    }

    public function getCommendArg($argName)
    {
        $args = $this->getCommendArgs();
        return isset($args[$argName]) ? $args[$argName] : '';
    }
}