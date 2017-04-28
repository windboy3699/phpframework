<?php
/**
 * Created by PhpStorm.
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: AdminUserModel.php 2017-04-26 $
 */
namespace App\Model;

use SPF\Database\BaseModel;

class SystemUserModel extends BaseModel
{
    protected $dbName = 'shop';

    protected $tableName = 'system_user';

    protected $pkName = 'id';

    public function getUsers($offset = 0, $limit = 20)
    {
        $where = [
            'LIMIT' => [$offset, $limit],
            'ORDER' => ['id' => 'DESC'],
        ];
        $data = $this->getDb()->select(
            $this->tableName(),
            '*',
            $where
        );
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

    public function deleteById($id)
    {
        return $this->getDb()->delete($this->tableName(), [
            $this->pkName => (int)$id
        ]);
    }
}