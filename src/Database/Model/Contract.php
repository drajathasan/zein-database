<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-02-01 22:28:06
 * @modify date 2022-02-01 22:28:06
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Database\Model;

use Zein\Database\Query\Builder;

abstract class Contract
{
    private static $Builder;
    protected $data = [];

    public static function __callStatic($name, $arguments)
    {
    }

    private function builder()
    {
    }
}