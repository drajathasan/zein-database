<?php
/**
 * @author drajathasan20@gmail.com
 * @email drajathasan20@gmail.com
 * @create date 2022-02-05 20:15:08
 * @modify date 2022-02-05 20:20:56
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Database\Query;

use ReflectionClass;

trait Reset
{
    public function resetProperty(array $propertyToReset = [])
    {
        if (count($propertyToReset) === 0)
        {
            $Class = new ReflectionClass($this);

            foreach ($Class as $prop) { 
                if (property_exists($this, $prop->getName())) { $this->{$prop->getName()} = ''; }
            }
        }
        else
        {
            foreach ($propertyToReset as $Property => $value) {
                $this->$Property = $value;
            }
        }
    }
}