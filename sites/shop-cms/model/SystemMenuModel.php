<?php
/**
 * Created by PhpStorm.
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: SystemMenuModel.php 2017-04-28 $
 */
namespace App\Model;

use SPF\Database\Model;

class SystemMenuModel extends Model
{
    protected $dbName = 'shop';

    protected $tableName = 'system_menu';

    public function getMenusByTopId($topid)
    {
        $data = $this->getDb()->select($this->tableName(), '*', [
            'AND' => [
                'topid' => $topid,
            ]
        ]);
        return $data;
    }

    public function getAllMenus()
    {
        $where = [
            'AND' => [
                'level' => 1,
                'visible' => 1,
            ], 'ORDER' => [
                'sort' => 'ASC',
                'id' => 'ASC',
            ]
        ];
        $level1 = $this->getDb()->select($this->tableName(), '*', $where);
        $where['AND']['level'] = 2;
        $level2 = $this->getDb()->select($this->tableName(), '*', $where);
        $where['AND']['level'] = 3;
        $level3 = $this->getDb()->select($this->tableName(), '*', $where);
        $data = array_merge($level1, $level2, $level3);
        return $data;
    }

    public function save($data, $pk = 0)
    {
        if (!$pk) {
            $ret = $this->getDb()->insert($this->tableName(), $data);
        } else {
            $ret = $this->getDb()->update($this->tableName(), $data, [
                $this->pkName => $pk,
            ]);
        }
        return $ret;
    }
}