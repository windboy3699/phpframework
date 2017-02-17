<?php
class Appcore_Model_UserModel extends SPF_Db_BaseModel
{
    protected $dbName = 'demo';

    protected $tableName = 'user';

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