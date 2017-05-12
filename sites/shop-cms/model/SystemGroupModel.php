<?php
/**
 * Created by PhpStorm.
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: SystemGroup.php 2017-04-28 $
 */
namespace App\Model;

use SPF\Db\Model;

class SystemGroupModel extends Model
{
    protected $dbName = 'shop';

    protected $tableName = 'system_group';

    public function getAllGroups()
    {
        $data = $this->getDb()->select(
            $this->tableName(),
            '*'
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
}