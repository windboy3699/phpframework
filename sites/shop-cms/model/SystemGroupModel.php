<?php
/**
 * Created by PhpStorm.
 *
 * @package
 * @author  XiaodongPan
 * @version $Id: SystemGroup.php 2017-04-28 $
 */
namespace App\Model;

use SPF\Database\BaseModel;

class SystemGroupModel extends BaseModel
{
    protected $dbName = 'shop';

    protected $tableName = 'system_group';

    public function getAll()
    {
        $data = $this->getDb()->select(
            $this->tableName(),
            '*'
        );
        return $data;
    }
}