<?php
/**
 * Created by PhpStorm.
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: SystemMenuModel.php 2017-04-28 $
 */
namespace App\Model;

use SPF\Database\BaseModel;

class SystemMenuModel extends BaseModel
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
        $data = $this->getDb()->select($this->tableName(), '*', [
            'AND' => [
                'visible' => 1,
            ]
        ]);
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