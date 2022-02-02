<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-02-01 23:05:23
 * @modify date 2022-02-01 23:05:23
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Database\Model;

use PDO;

trait Shorthand
{
    public function find($primaryKey)
    {
        // Create static instance
        $Static = new static;

        // Igniate query builder
        $Builder = $Static->Builder();

        // Make query statement
        $State = $Builder->where('biblio_id', $primaryKey)->from($Static->table)->get();
        
        if (count($State->Data) > 0)
        {
            return $State;
        }
    }    
}
