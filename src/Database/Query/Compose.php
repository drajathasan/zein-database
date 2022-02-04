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

use PDO;
use PDOStatement;
use Zein\Database\Dages\Model as SLiMSModel;

trait Compose
{
    public function result()
    {
        $Marker = $this->MarkType . 'Mark';
        $Result = '';
        switch ($this->State) {
            case 'select':
                $Result = 'SELECT ' . $this->Column . ' FROM ' . $this->isHavingAlias($this->Table);
                if (count($this->Join)) 
                    $Result .= ' ' . implode(' ', $this->Join);
                if (count($this->Criteria)) 
                    $Result .= ' WHERE ' . $this->$Marker($this->Criteria, 'where');
                if (!empty($this->OrderBy))
                    $Result .= ' ORDER BY ' . $this->OrderBy;
                if ($this->Limit > 0)
                    $Result .= ' LIMIT ' . $this->cleanHarmCharacter($this->Limit);
                if (is_numeric($this->Offset))
                    $Result .= ' OFFSET ' . $this->cleanHarmCharacter($this->Offset);
                break;
            
            default:
                # code...
                break;
        }

        return $Result;
    }

    public function many(PDOStatement $Statement)
    {
        $Result = [];
        while ( $Data = $Statement->fetch(PDO::FETCH_ASSOC) ) {
            $Model = new SLiMSModel($this->removeAlias($this->Table));
            foreach ($Data as $key => $value) {
                $Model->$key = $value;
            }
            $Result[] = $Model;
        }
        return $Result;
    }

    public function single(PDOStatement $Statement)
    {
        $Model = new SLiMSModel($this->removeAlias($this->Table));
        
        foreach ($Statement->fetch(PDO::FETCH_ASSOC) as $key => $value) {
            $Model->$key = $value;
        }

        return $Model;
    }
}