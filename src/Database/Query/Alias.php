<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-02-04 10:22:32
 * @modify date 2022-02-04 10:22:32
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Database\Query;

trait Alias
{
    /**
     * Remove dot from column or table
     * 
     * @param string $Column
     * @return string
     */
    public function removeDot(string $Column)
    {
        return trim(substr_replace($Column, '', 0, strpos($Column, '.')), '.');
    }

    /**
     * Alias character check
     * 
     * @param string $Column
     * @return string
     */
    public function isHavingAlias(string $Column)
    {
        if (preg_match('/(\sAS\s)|(\sas\s)/i', $Column, $Matching))
        {
            $Column = explode($Matching[0], $Column);
            return $this->setSeparator($Column[0], '`') . ' AS ' . $this->setSeparator($Column[1], '`');
        }

        return $this->setSeparator($Column, '`');
    }

    /**
     * Remove alias from column
     * 
     * @param string $Column
     * @return string
     */
    public function removeAlias(string $Column)
    {
        if (preg_match('/(\sAS\s)|(\sas\s)/i', $Column, $Matching))
        {
            $Table = explode($Matching[0], $this->cleanHarmCharacter($Column));
            return $Table[0]??$Column;
        }
        return $Column;
    }
}