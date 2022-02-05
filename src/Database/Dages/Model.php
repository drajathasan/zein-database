<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-02-05 12:04:19
 * @modify date 2022-02-05 12:04:19
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Database\Dages;

class Model
{
    protected $Table = '';
    protected $Data = [];

    public function __construct(string $TableName)
    {
        $this->Table = $TableName;
    }

    public function __set($name, $value)
    {
        $this->Data[$name] = $value;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->Data)) return $this->Data[$name];
    }
}