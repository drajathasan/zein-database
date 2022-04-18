<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-02-01 22:03:39
 * @modify date 2022-04-18 10:19:16
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
            case 'insert':
            case 'replace':
                return implode(' = ?, ', array_keys($Column)) . ' = ? ';
                break;
            
            case 'where':
                $Criteria = '';
                foreach ($Column as $column => $value) {
                    if (!is_array($value))
                    {
                        $Criteria .= $this->setSeparator($column) . ' = ? AND ';
                    }
                    else
                    {
                        $Criteria .= $this->setSeparator($column) . ' IN ('. rtrim(str_repeat('?,', count($value)), ',') .') AND ';
                        unset($this->Criteria[$column]);
                        $this->Criteria = array_merge($this->Criteria, $value);
                    }
                }
                $this->Criteria = array_values($this->Criteria);
                return substr_replace($Criteria, '', -5);
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
                    $Criteria .= $this->setSeparator($column) . ' = :' . $this->removeDot($this->cleanHarmCharacter($column)) . ' AND ';
                }
                foreach ($this->Criteria as $key => $value) {
                    if (strpos($key, '.'))
                    {
                        $this->Criteria[$this->removeDot($key)] = $value;
                        unset($this->Criteria[$key]);
                    }
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
     * @return string
     */
    public function setColumnSeparator(array $Column):string
    {
        $ColumnWithSeparator = [];
        foreach ($Column as $column => $value) {
            if (is_string($value))
            {
                $ColumnWithSeparator[] = $this->isHavingAlias($this->cleanHarmCharacter($value));
            } else if (is_callable($value))
            {
                $ColumnWithSeparator[] = $this->isHavingAlias($value());
            }
        }
        return implode(', ', $ColumnWithSeparator);
    }

    /**
     * Set separator
     * 
     * @param string $Word
     * @param string $Separator
     * @return string
     */
    public function setSeparator(string $Word):string
    {
        if (strpos($Word, '.'))
        {
            $Word = explode('.', trim($Word));
            return trim($this->Separator) . $this->cleanHarmCharacter($Word[0]) . trim($this->Separator) . '.' .
                   trim($this->Separator) . $this->cleanHarmCharacter($Word[1]) . trim($this->Separator);
        }

        return trim($this->Separator) . $this->cleanHarmCharacter($Word) . trim($this->Separator);
    }

    /**
     * Clean Harmfull Character
     * 
     * @param string $Character
     * @return string
     */
    public function cleanHarmCharacter(string $Character)
    {
        return str_replace(['\'','"','`','-',';','%'], '', $Character);
    }
}