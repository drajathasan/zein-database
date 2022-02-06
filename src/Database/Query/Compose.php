<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-02-01 23:26:54
 * @modify date 2022-02-05 20:53:35
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Database\Query;

use PDO;
use PDOStatement;
use Zein\Database\Dages\SLiMSModel;

trait Compose
{
    public function result()
    {
        $Marker = $this->MarkType . 'Mark';
        $Result = '';
        
        switch ($this->State) {

            // Select statement
            case 'select':
                $Result = 'SELECT ' . $this->Column . ' FROM ' . $this->isHavingAlias($this->Table);
                if (count($this->Join)) 
                    $Result .= ' ' . implode(' ', $this->Join);
                if (count($this->Criteria)) 
                    $Result .= ' WHERE ' . $this->$Marker($this->Criteria, 'where');
                if (!empty($this->GroupBy))
                    $Result .= ' GROUP BY ' . $this->cleanHarmCharacter($this->GroupBy);
                if (!empty($this->OrderBy))
                    $Result .= ' ORDER BY ' . $this->cleanHarmCharacter($this->OrderBy);
                if ($this->Limit > 0)
                    $Result .= $this->generateLimit($this->Limit, (is_numeric($this->Offset)?$this->Offset:0));
                break;

            // Delete
            case 'delete':
                $Result = 'DELETE FROM ' . $this->isHavingAlias($this->Table);
                if (count($this->Criteria)) 
                    $Result .= ' WHERE ' . $this->$Marker($this->Criteria, 'where');
                break;

            // Insert statement
            case 'insert':
                $Result = 'INSERT INTO ' . $this->setSeparator($this->Table) . ' SET ';
                $Result .= $this->$Marker($this->Data, $this->State);
                $this->Criteria = array_values($this->Data);
                break;

            // Update statement
            case 'update':
                $Result = 'UPDATE ' . $this->setSeparator($this->Table) . ' SET ';
                $Result .= $this->$Marker($this->Data, $this->State);
                if (count($this->Criteria)) 
                    $Result .= ' WHERE ' . $this->$Marker($this->Criteria, 'where');

                $this->Criteria = array_values(array_merge($this->Data, $this->Criteria));
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
            $Model = new SLiMSModel($this->removeAlias($this->Table), $this->PrimaryKey);
            foreach ($Data as $key => $value) {
                $Model->$key = $value;
            }
            $Result[] = $Model;
        }
        return $Result;
    }

    public function single(PDOStatement $Statement)
    {
        $Model = new SLiMSModel($this->removeAlias($this->Table), $this->PrimaryKey);
        foreach ($Statement->fetch(PDO::FETCH_ASSOC) as $key => $value) {
            $Model->$key = $value;
        }

        return $Model;
    }
}