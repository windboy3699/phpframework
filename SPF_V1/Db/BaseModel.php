<?php
/**
 * Model简单封装
 * Todo 对象化 查询缓存
 * @package /SPF/Base
 * @author  XiaodongPan
 * @version $Id: BaseModel.php 2016-12-10 $
 */
abstract class SPF_Db_BaseModel
{
    const DEFAULT_LIMIT = 500;

    const DEFAULT_OFFSET = 0;

    /**
     * @var db名称
     */
    protected $dbName;

    /**
     * @var table名称
     */
    protected $tableName;

    /**
     * @var 分表table前缀
     */
    protected $tableNamePrefix;

    /**
     * @var string 主键字段名称
     */
    protected $pkName = 'id';

    /**
     * @var bool 是否分表
     */
    protected $sharding = false;

    /**
     * @var db实例
     */
    protected $db = null;

    /**
     * 分表因子改变后，表名称也跟着改变
     * 子类实现因子和表的关系
     * @param mixed $factor
     */
    public function changeFactor($factor)
    {
        $this->factor = $factor;
    }

    /**
     * 获取table名称
     * @return table名称
     * @throws SPF_Exception
     */
    public function tableName()
    {
        if($this->sharding === true && $this->factor === '') {
            throw new SPF_Exception('sharding table changeFactor first tableName');
        }
        return $this->tableName;
    }

    /**
     * 获取db实例
     * @param bool|false $alwaysMaster
     * @return mixed
     * @throws SPF_Exception
     */
    public function getDb($alwaysMaster = false)
    {
        if($this->sharding === true && $this->factor === '') {
            throw new SPF_Exception('sharding table changeFactor first tableName');
        }
        $config = SPF::getInstance()->getConfig('db_'.$this->dbName, 'server');
        return SPF_Db::getInstance($config, $alwaysMaster);
    }

    /**
     * 根据ID获取数据
     * @param $id
     * @return mixed
     * @throws SPF_Exception
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
     * @param array $ids
     * @param bool|false $idKey key是否转换成主键
     * @return array
     * @throws SPF_Exception
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