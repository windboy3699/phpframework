<?php
/**
 * Model
 *
 * @package SPF.Db
 * @author  XiaodongPan
 * @version $Id: Model.php 2017-04-12 $
 */
namespace spf\db;

use spf\SPF;
use spf\db\Factory as DbFactory;

abstract class Model
{
    const DEFAULT_LIMIT = 500;

    const DEFAULT_OFFSET = 0;

    /**
     * db名称
     *
     * @var string
     */
    protected $dbName;

    /**
     * table名称
     *
     * @var string
     */
    protected $tableName;

    /**
     * 分表table前缀
     *
     * @var string
     */
    protected $tableNamePrefix;

    /**
     * 主键字段名称
     *
     * @var string
     */
    protected $pkName = 'id';

    /**
     * 是否分表
     *
     * @var bool
     */
    protected $sharding = false;

    /**
     * @var db实例
     */
    protected $db = null;

    /**
     * 分表因子改变后，表名称也跟着改变
     * 子类实现因子和表的关系
     *
     * @param mixed $factor
     */
    public function changeFactor($factor)
    {
        $this->factor = $factor;
    }

    /**
     * 获取table名称
     *
     * @return table名称
     * @throws DbException
     */
    public function tableName()
    {
        if($this->sharding === true && $this->factor === '') {
            throw new DbException('sharding table changeFactor first');
        }
        return $this->tableName;
    }

    /**
     * 获取db实例
     *
     * @param bool|false $alwaysMaster
     * @return mixed
     * @throws DbException
     */
    public function getDb($alwaysMaster = false)
    {
        if($this->sharding === true && $this->factor === '') {
            throw new DbException('sharding table changeFactor first');
        }
        $config = SPF::App()->getConfig('db_'.$this->dbName, 'server');
        return DbFactory::getInstance($config, $alwaysMaster);
    }

    /**
     * 根据ID获取数据
     *
     * @param $id
     * @return mixed
     * @throws DbException
     */
    public function findById($id)
    {
        $where = [
            $this->pkName => $id
        ];
        return $this->getDb()->fetchRow($this->tableName(), '*', $where);
    }

    /**
     * 根据多个ID查询数据
     *
     * @param array $ids
     * @param bool|false $idKey key是否转换成主键
     * @return array
     * @throws DbException
     */
    public function findByIds(array $ids, $key2id = true)
    {
        $where = [
            $this->pkName => $ids
        ];
        $data = $this->getDb()->select($this->tableName(), '*', $where);
        if(!$key2id) {
            return $data;
        }
        $ret = [];
        foreach ($data as $item) {
            $ret[$item[$this->pkName]] = $item;
        }
        return $ret;
    }
}