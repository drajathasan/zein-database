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
use Zein\Database\Dages\Model as SLiMSModel;

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
                if (!empty($this->OrderBy))
                    $Result .= ' ORDER BY ' . $this->OrderBy;
                if ($this->Limit > 0)
                    $Result .= $this->generateLimit($this->Limit, (is_numeric($this->Offset)?$this->Offset:0));
                break;

            // Delete
            case 'delete':
                $Result = 'DELETE FROM ' . $this->isHavingAlias($this->Table);
                if (count($this->Criteria)) 
                    $Result .= ' WHERE ' . $this->$Marker($this->Criteria, 'where');
                break;

            // Update && statement
            case 'insert':
            case 'update':
                $Result = $this->cleanHarmCharacter(strtoupper($this->State)) . ' ' . $this->setSeparator($this->Table) . ' SET ';
                $Result .= $this->$Marker($this->Data, $this->State);
                if (count($this->Criteria)) 
                    $Result .= ' WHERE ' . $this->$Marker($this->Criteria, 'where');

                $this->Criteria = array_values(array_merge($this->Data, $this->Criteria));

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
        $Model = new SLiMSModel($this->removeAlias($this->Table), $this->Connection, $this->PrimaryKey);
        foreach ($Statement->fetch(PDO::FETCH_ASSOC) as $key => $value) {
            $Model->$key = $value;
        }
        
        $Model->removeLink();

        return $Model;
    }
}