<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-02-02 13:53:37
 * @modify date 2022-02-05 20:34:42
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Database\Dages;

use ReflectionClass;
use PDO;
use Zein\Database\Query\Builder;
use Zein\Database\Connection\Connector;

abstract class ModelContract 
{
    use Shorthand;

    /**
     * Builder and Connection
     */
    private static $Builder;
    private static $Connection;

    /**
     * Connection profile
     */
    protected $ConnectionProfile = [];

    /**
     * Primary Key
     */
    protected $PrimaryKey = 'id';

    /**
     * 
     */
    protected $Table = '';

    /**
     * 
     */
    protected $Data = [];

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

    protected function createConnectionInit() {}

    private function builder()
    {
        if (is_null(self::$Connection)) 
        {
            $this->createConnectionInit();
            self::$Connection = new Connector($this->ConnectionProfile);
        }

        $Class = new ReflectionClass($this);

        if (empty($this->Table))
        {
            $this->Table = strtolower($Class->getShortName());
        }

        self::$Builder = new Builder(self::$Connection, $this->Table, $this->PrimaryKey);

        return self::$Builder;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->Data)) return $this->Data[$name];
    }

    public function __set($name, $value)
    {
        $this->Data[$name] = $value;
    }

    public function removeLink()
    {
        unset($this->ConnectionProfile);
    }
}
