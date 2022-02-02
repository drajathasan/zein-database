<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-02-01 22:28:06
 * @modify date 2022-02-01 22:28:06
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Database\Model;

use ReflectionClass;
use Zein\Database\Connection;
use Zein\Database\Query\Builder;

class Model extends Contract
{
    private static $Builder;
    private static $Connection;

    protected $table = '';
    protected $data = [];

    use Shorthand;

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
    }

    private function builder()
    {
        if (is_null(self::$Connection)) self::$Connection = new Connection('mysql:host=localhost;dbname=s94', 'drajat', '20091996');

        $Class = new ReflectionClass($this);
        if (empty($this->Table)) $this->Table = strtolower($Class->getShortName());

        if (is_null(self::$Builder))
            self::$Builder = new Builder(self::$Connection, $this->Table);

        return self::$Builder;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->data)) return $this->data[$name];
    }
}