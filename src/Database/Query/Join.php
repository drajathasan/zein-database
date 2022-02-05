<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-02-04 08:47:56
 * @modify date 2022-02-04 08:47:56
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Database\Query;

trait Join
{
    public function __call($name, $arguments)
    {
        if (preg_match('/Join/i', $name)) return $this->make($name, $arguments);
        return $this;
    }

    public static function __callStatic($name, $arguments)
    {
        if (preg_match('/Join/i', $name)) return (new static)->make($name, $arguments);
        return $this;
    }

    /**
     * Make Join Clause
     * 
     * @param string $name
     * @param string $arguments
     * @return object
     */
    private function make($name, $arguments)
    {
        $joinTable = $this->setSeparator($arguments[0], '`');
        $joinType = strtoupper($this->cleanHarmCharacter($this->isHavingAlias(str_replace('Join', '', $name)))) . ' JOIN ' . 
                    $this->isHavingAlias($joinTable);

        $joinRaw = '';

        if (is_array($arguments[1][0]))
        {
            foreach ($arguments[1] as $index => $joinOption) {
                foreach ($arguments[$index] as $innserOption) {
                    $joinRaw .= $this->setSeparator($innserOption[0], '`') .
                                $this->cleanHarmCharacter($innserOption[1]) .
                                $this->setSeparator($innserOption[2], '`') . 
                                ' AND ';
                }
                $joinRaw = substr_replace($joinRaw, '', -6);
            }
        }
        else
        {
            $joinRaw .= $this->setSeparator($arguments[1][0], '`') . ' ' .
                        $this->cleanHarmCharacter($arguments[1][1]) . ' ' .
                        $this->setSeparator($arguments[1][2], '`');
        }

        $this->Join[] = $joinType . ' ON ' . $joinRaw;

        return $this;
    }
}
