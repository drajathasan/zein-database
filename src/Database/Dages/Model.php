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

class Model
{
    private $Link;
    protected $Table = '';
    protected $PrimaryKey = '';
    protected $Data = [];

    use Shorthand;

    public function __construct(string $TableName = '', $Link = '', $PrimaryKey = '')
    {
        $this->Table = $TableName;
        $this->Link = $Link;
        $this->PrimaryKey = $PrimaryKey;
    }

    public function __set($name, $value)
    {
        $this->Data[$name] = $value;
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