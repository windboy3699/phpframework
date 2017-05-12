<?php
/**
 * Application
 *
 * @package SPF.Application
 * @author  XiaodongPan
 * @version $Id: Application.php 2017-04-26 $
 */
namespace SPF\Application;

use SPF\Config\Repository as ConfigRepository;
use SPF\Db\Factory as DbFactory;
use SPF\Cache\Memcache;
use SPF\Cache\Redis;

class Application
{
    /**
     * @var array
     */
    protected $configPaths = [];

    /**
     * @var array
     */
    protected $components = [];

    /**
     * 设置配置文件路径
     *
     * @param $paths
     */
    public function setConfigPaths(array $paths)
    {
        $this->configPaths = $paths;
    }

    /**
     * 获取配置
     *
     * @param $name
     * @param string $file
     * @param null $default
     * @return mixed
     */
    public function getConfig($name, $file = 'common', $default = null)
    {
        if (!isset($this->components['configRepository'])) {
            $this->components['configRepository'] = new ConfigRepository($this->configPaths);
        }
        return $this->components['configRepository']->get($name, $file, $default);
    }

    /**
     * 获取Db实例
     *
     * @param $dbname
     * @param bool|false $alwaysMaster
     * @return mixed
     * @throws \SPF\Db\Exception
     */
    public function getDb($dbname, $alwaysMaster = false)
    {
        $config = $this->getConfig('db_' . $dbname, 'server');
        return DbFactory::getInstance($config, $alwaysMaster);
    }

    /**
     * 获取Memcache实例
     *
     * @return mixed
     * @throws \SPF\Cache\Exception
     */
    public function getMemcache()
    {
        if (!isset($this->components['memcache'])) {
            $this->components['memcache'] = new Memcache($this->getConfig('memcache', 'server'));
        }
        return $this->components['memcache'];
    }

    /**
     * 获取Redis实例
     *
     * @return mixed
     * @throws \SPF\Cache\Exception
     */
    public function getRedis()
    {
        if (!isset($this->components['redis'])) {
            $this->components['redis'] = new Redis($this->getConfig('redis', 'server'));
        }
        return $this->components['redis'];
    }
}