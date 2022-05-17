<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-05-17 21:10:23
 * @modify date 2022-05-17 21:30:09
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Database\Dages;

trait Magic
{
    public function __call($name, $arguments)
    {
        $this->Builder();

        if (method_exists(self::$Builder, $name))
            return call_user_func_array([self::$Builder, $name], $arguments);

        if (method_exists($this, $name))
            return call_user_func_array([$this, $name], $arguments);
    }

    public static function __callStatic($name, $arguments)
    {
        $Static = new static;
        $Static->Builder();

        if (method_exists(self::$Builder, $name))
            return call_user_func_array([self::$Builder, $name], $arguments);

        if (method_exists($Static, $name))
            return call_user_func_array([$Static, $name], $arguments);

        return @call_user_func_array([self::$Builder, $name], $arguments);
    }

    public function __isset($key)
    {
        return isset($this->Data[$key]);
    }

    public function __unset($key)
    {
        unset($this->Data[$key]);
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->Data)) return $this->Data[$name];
    }

    public function __set($name, $value)
    {
        $this->Data[$name] = $value;
    }

    public function __toString()
    {
        return json_encode($this->Data);
    }
}