<?php
/**
 * Created by PhpStorm.
 * @package /
 * @author  XiaodongPan
 * @version $Id: UserModel.php 2017-03-16 $
 */
class Kernel_Game_Model_UserModel extends SPF_Db_BaseModel
{
    protected $dbName = 'demo';

    protected $tableName = 'user';

    public static function say()
    {
        echo 'say hello';
    }

    public function findByGroup($group)
    {
        $where = [
            'AND' => [
                'group' => $group,
                'visible' => 1,
            ],
            'ORDER' => ['sort' => 'DESC'],
        ];
        return $this->getDb()->select($this->tableName(), '*', $where);
    }
}