<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-02-01 22:03:39
 * @modify date 2022-02-01 22:03:39
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Database;

trait Utils
{
    /**
     * Make prepare data with question mark style
     *
     * @param array $Column
     * @param string $State
     * @return string
     */
    public function questionMark(array $Column, string $State = 'insert'):string
    {
        switch ($State) {
            case 'update':
            case 'insertset':
            case 'replace':
                return substr_replace( implode(' = ?, ', array_keys($Column)) , '', -1);
                break;
            
            case 'where':
                return substr_replace( implode(' = ? AND ', array_keys($Column)) , '', -5);
                break;
            
            default:
                return substr_replace( str_repeat("?,", count($Column) ) , '', -1);
                break;
        }
    }

    /**
     * Make prepare data with named mark style
     *
     * @param array $Column
     * @param string $State
     * @return string
     */
    public function namedMark(array $Column, string $State = 'insert'):string
    {
        switch ($State) {
            case 'update':
            case 'insertset':
            case 'replace':
                return substr_replace( ':' . implode(' = ?, ', array_keys($Column)) , '', -1);
                break;
            
            case 'where':
                $Criteria = '';
                foreach ($Column as $column => $value) {
                    $Criteria .= $this->setSeparator($column, "`") . ' = :' . $this->cleanHarmCharacter($column) . ' AND ';
                }
                return substr_replace($Criteria, '', -5);
                break;
            
            default:
                return ':' . implode(', :', array_keys($Column));
                break;
        }
    }

    /**
     * Make column separator
     *
     * @param array $Column
     * @param string $Separator
     * @return array
     */
    public function setColumnSeparator(array $Column, string $Separator):array
    {
        $ColumnWithSeparator = [];
        foreach ($Column as $column => $value) {
            $ColumnWithSeparator[$this->setSeparator($column, $Separator)] =  $this->cleanHarmCharacter($value);
        }
        return $ColumnWithSeparator;
    }

    public function setSeparator(string $Word, string $Separator):string
    {
        return trim($Separator) . $this->cleanHarmCharacter($Word) . trim($Separator);
    }

    public function cleanHarmCharacter(string $Character)
    {
        return preg_replace('/[\'\`\"\s+]/i', '', $Character);
    }
}