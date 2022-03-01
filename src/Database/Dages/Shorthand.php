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
use Zein\Database\Query\Builder;

trait Shorthand
{
    public static function find($primaryKey)
    {
        // Create static instance
        $Static = new static;

        // Igniate query builder
        $Builder = $Static->Builder();

        // Make query statement
        $State = $Builder->where($Static->PrimaryKey, $primaryKey)->from($Static->Table)->get(true);

        return $State;
    }
    
    public static function all(array $Column = [])
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

    public static function create(array $Data)
    {
        // Create static instance
        $Static = new static;

        // Igniate query builder
        $Builder = $Static->Builder();

        return $Builder->insert($Data);
    }

    public static function createBatch(array $Data)
    {
        // Create static instance
        $Static = new static;

        // Igniate query builder
        $Builder = $Static->Builder();

        $Result = 0;
        foreach ($Data as $Column) {
            if ($Builder->insert($Column) > 0) $Result++;
        }

        return $Result;
    }

    public function save()
    {
        $Builder = $this->getBuilder();
        
        if ($this->Timestamp && !isset($this->Data[self::UPDATED_AT])) $this->Data[self::UPDATED_AT] = date('Y-m-d H:i:s');

        $update = $this->where($this->PrimaryKey, $this->Data[$this->PrimaryKey])->update($this->Data);
        
        if ($update == 0)
        {
            if ($this->Timestamp && !isset($this->Data[self::CREATED_AT])) $this->Data[self::CREATED_AT] = date('Y-m-d H:i:s');
            return $this->insert($this->Data);
        }

        return $update;
    }
    
    public function delete()
    {
        $Builder = $this->getBuilder();

        if (count($this->Data) > 0)
        {
            return $this->where($this->PrimaryKey, $this->Data[$this->PrimaryKey])->delete();
        }
    }
}
