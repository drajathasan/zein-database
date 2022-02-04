<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-02-01 23:05:23
 * @modify date 2022-02-01 23:05:23
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Database\Dages;

use PDO;
use ReflectionClass;

trait Shorthand
{
    public function find($primaryKey)
    {
        // Create static instance
        $Static = new static;

        // Igniate query builder
        $Builder = $Static->Builder();

        // Make query statement
        $State = $Builder->where($Static->PrimaryKey, $primaryKey)->from($Static->Table)->get();

        return $State;
    }
    
    public function all(array $Column = [])
    {
        // Create static instance
        $Static = new static;

        // Igniate query builder
        $Builder = $Static->Builder();

        if (count($Column)) call_user_func_array([$Builder, 'select'], $Column);

        // Make query statement
        $State = $Builder->from($Static->Table)->get();
        
        if (count($State)) return $State;
    }
}
