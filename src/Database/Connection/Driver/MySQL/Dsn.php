<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-02-05 11:45:08
 * @modify date 2022-02-05 11:45:08
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Database\Connection\Driver\MySQL;

trait Dsn
{
    public static function init(array $Attribute)
    {
        $DSNString = 'mysql:';
        foreach ($Attribute as $key => $value) {
            $DSNString .= $key . '=' . $value . ';';
        }
        return substr_replace($DSNString, '', -1);
    }
}