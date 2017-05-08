<?php
/**
 * Created by PhpStorm.
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: AdminUserModel.php 2017-04-26 $
 */
namespace App\Model;

use SPF\Database\Model;

class SystemUserModel extends Model
{
    protected $dbName = 'shop';

    protected $tableName = 'system_user';

    public function checkLogin($username, $password)
    {
        $where = [
            'AND' => [
                'username' => $username,
                'password' => md5($password),
            ]
        ];
        return $this->getDb()->fetchRow($this->tableName(), '*', $where);
    }

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

    public function getUsersCount()
    {
        return $this->getDb()->count($this->tableName());
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