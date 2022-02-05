<?php
/**
 * @author drajathasan20@gmail.com
 * @email drajathasan20@gmail.com
 * @create date 2022-02-05 17:53:26
 * @modify date 2022-02-05 18:08:31
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Database\Query;

trait Limit
{
    public function generateLimit(int $Limit, $Offset = 0)
    {
        $Driver = $this->Connection->getDriver();
        $LimitClass = '\Zein\Database\Connection\Driver\\' . $Driver . '\Limit';

        return $LimitClass::generate($Limit, $Offset);
    }
}