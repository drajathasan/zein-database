<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-02-01 23:26:54
 * @modify date 2022-02-01 23:26:54
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Database\Query;

trait Compose
{
    public function result()
    {
        $Marker = $this->MarkType . 'Mark';
        $Result = '';
        switch ($this->State) {
            case 'select':
                $Result = 'SELECT ' . $this->Column . ' FROM ' . $this->Table;
                if (count($this->Criteria)) 
                    $Result = $Result . ' where ' . $this->$Marker($this->Criteria, 'where');
                break;
            
            default:
                # code...
                break;
        }

        return $Result;
    }
}