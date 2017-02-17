<?php
/**
 * 配置类
 * @package /SPF/Base
 * @author  XiaodongPan
 * @version $Id: Config.php 2016-11-09 $
 */
class SPF_Config
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