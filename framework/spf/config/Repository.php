<?php
/**
 * 配置库
 *
 * @package SPF.Config
 * @author  XiaodongPan
 * @version $Id: Repository.php 2017-05-08 $
 */
namespace spf\config;

class Repository
{
    private $configs = [];

    private $paths = [];

    public function __construct($paths)
    {
        $this->paths = $paths;
    }

    public function addPath($path)
    {
        $this->paths[] = $path;
    }

    public function get($name = null, $file = 'common', $default = null)
    {
        if (!isset($this->configs[$file])) {
            $config = $this->load($file);
            $this->configs[$file] = $config;
        } else {
            $config = $this->configs[$file];
        }
        if (!$name) {
            $config = $config ? $config : $default;
        } else {
            $config = $config && isset($config[$name]) ? $config[$name] : $default;
        }
        return $config;
    }

    public function load($file = "common")
    {
        foreach ($this->paths as $path) {
            $filePath = $path . DIRECTORY_SEPARATOR . $file . '.php';
            if (file_exists($filePath)) {
                return include($filePath);
            }
        }
        return [];
    }
}