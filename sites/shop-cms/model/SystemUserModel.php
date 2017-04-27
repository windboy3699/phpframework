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
}