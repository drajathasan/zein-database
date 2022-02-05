<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-02-01 23:05:23
 * @modify date 2022-02-05 21:43:28
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
        
        return $State;
    }

    public function create(array $Data)
    {
        // Create static instance
        $Static = new static;

        // Igniate query builder
        $Builder = $Static->Builder();

        return $Builder->insert($Data);
    }

    public function save()
    {
        if (!method_exists($this, 'Builder'))
        {
            $Builder = new \Zein\Database\Query\Builder($this->Link, $this->Table, $this->PrimaryKey);
            $Count = $Builder->where($this->PrimaryKey, $this->{$this->PrimaryKey})->count();

            $Builder->resetProperty(['Criteria' => []]);

            return $Builder->where($this->PrimaryKey, $this->{$this->PrimaryKey})->update($this->Data, false);
        }
        else
        {
            $this->resetProperty(['Criteria' => []]);
            return $this->insert($this->Data);
        }
    }
}
