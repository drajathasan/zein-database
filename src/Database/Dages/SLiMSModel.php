<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-02-05 12:04:19
 * @modify date 2022-02-05 20:54:15
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Database\Dages;

use PDO;

class SLiMSModel extends SLiMSModelContract
{
    protected $Table = '';
    protected $PrimaryKey = '';
    protected $Data = [];
    protected $Timestamp = true;
    protected $Dateformat = 'Y-m-d H:i:s';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    use Shorthand;

    public function __construct(string $TableName = '', $PrimaryKey = '')
    {
        $this->Table = $TableName;
        $this->PrimaryKey = $PrimaryKey;
    }

    public function __set($name, $value)
    {
        if (!property_exists($this, $name)) $this->Data[$name] = $value;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->Data)) return $this->Data[$name];
    }

    public function removeLink()
    {
        $this->Link = null;
        unset($this->Link);
    }
}