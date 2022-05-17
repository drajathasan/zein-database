<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-05-17 21:12:36
 * @modify date 2022-05-17 21:30:23
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Database\Dages;

trait Arrayable
{
    public function count()
    {
        return $this->Data;
    }

    public function jsonSerialize()
    {
        return $this->Data;
    }

    public function toArray()
    {
        return $this->Data;
    }
}