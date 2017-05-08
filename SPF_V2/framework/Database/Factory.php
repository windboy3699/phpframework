<?php
/**
 * DbFactory
 *
 * @package SPF.Database
 * @author  XiaodongPan
 * @version $Id: Factory.php 2017-04-12 $
 */
namespace SPF\Database;

use PDO;
use Medoo;
use SPF\Database\Factory as DbFactory;

require_once dirname(dirname(dirname(__FILE__))) . '/lib/Medoo/Medoo.php';

class Factory
{
    private $master = null;

    private $slave = null;

    private $config = [];

    private static $dbs = [];

    private $alwaysMaster = false;

    private $options = [
        'database_type' => 'mysql',
        'database_name' => 'sports',
        'server' => '127.0.0.1',
        'username' => 'root',
        'password' => 'root',
        'charset' => 'utf8',
        'option' => [
            PDO::ATTR_CASE => PDO::CASE_NATURAL
        ]
    ];

    /**
     * 初始传入配置
     *
     * @param array $config
     * @param bool $alwaysMaster
     */
    public function __construct($config, $alwaysMaster = false)
    {
        if (empty($config['master']) || empty($config['slave'])) {
            throw new DatabaseException('数据库配置错误');
        }

        $this->config['master'] = array_merge($this->options, $config['master']);
        $count = count($config['slave']);
        $idx = $count == 1 ? 0 : mt_rand(0, $count - 1);
        $this->config['slave'] = array_merge($this->options, $config['slave'][$idx]);

        $this->alwaysMaster = $alwaysMaster;
    }

    /**
     * 生成db实例
     *
     * @param $config
     * @param bool|false $alwaysMaster
     * @param bool|false $refresh
     * @return mixed
     */
    public static function getInstance($config, $alwaysMaster = false)
    {
        $dbname = $config['master']['database_name'];
        $key = $alwaysMaster ? $dbname . '1' : $dbname . '0';
        if (!isset($dbs[$key])) {
            self::$dbs[$key] = new DbFactory($config, $alwaysMaster);
        }
        return self::$dbs[$key];
    }

    /**
     * 获取主数据库连接
     *
     * @return Medoo
     */
    public function getMaster()
    {
        if ($this->master === null) {
            $this->master = $this->getMedoo($this->config['master']);
        }
        return $this->master;
    }

    /**
     * 获取从数据库连接
     *
     * @return Medoo
     */
    public function getSlave()
    {
        if ($this->alwaysMaster || empty($this->config['slave'])) {
            return $this->getMaster();
        }
        if ($this->slave === null) {
            $this->slave = $this->getMedoo($this->config['slave']);
        }
        return $this->slave;
    }

    /**
     * 获取数据库连接
     *
     * @param array $config
     * @return Medoo
     */
    protected function getMedoo(array $config)
    {
        return new Medoo($config);
    }

    /**
     * 查询走Slave
     *
     * @param $table
     * @param $join
     * @param null $columns
     * @param null $where
     * @return mixed
     */
    public function select($table, $join, $columns = null, $where = null)
    {
        return $this->getSlave()->select($table, $join, $columns, $where);
    }

    /**
     * 获取单行
     *
     * @param $table
     * @param $join
     * @param null $columns
     * @param null $where
     * @return array|mixed
     */
    public function fetchRow($table, $join, $columns = null, $where = null)
    {
        $columns['LIMIT'] = [0, 1];
        $data = $this->select($table, $join, $columns, $where);
        return $data ? current($data) : [];
    }

    /**
     * 新增或更新
     *
     * @param $table
     * @param array $bind
     * @param null $where
     * @return int|PDOStatement
     */
    public function save($table, array $data, $where = null)
    {
        if ($where && $this->select($table, '*', $where)) {
            return $this->update($table, $data, $where);
        } else {
            return $this->insert($table, $data);
        }
    }

    /**
     * 执行Medoo原始方法
     *
     * @param string $method
     * @param array $args
     * @return mixed
     */
    public function __call($method, array $args)
    {
        return call_user_func_array(array($this->getMaster(), $method), $args);
    }
}
