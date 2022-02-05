<?php
/**
 * @author drajathasan20@gmail.com
 * @email drajathasan20@gmail.com
 * @create date 2022-02-05 17:58:06
 * @modify date 2022-02-05 18:05:41
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Database\Connection\Driver\Mysql;

class Limit
{
    public static function generate(int $Limit, int $Offset = 0)
    {
        return ' LIMIT ' . $Limit . ' OFFSET ' . $Offset;
    }
}