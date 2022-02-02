<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-02-02 13:53:37
 * @modify date 2022-02-02 13:53:37
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Database\Model;

use ReflectionClass;
use Zein\Database\Query\Builder;
use Zein\Database\SLiMSConnection;

class SLiMSModel 
{
    use Shorthand;

    /**
     * Builder and Connection
     */
    private static $Builder;
    private static $Connection;

    /**
     * Primary Key
     */
    protected $PrimaryKey = 'id';

    /**
     * 
     */
    protected $table = '';

    /**
     * 
     */
    protected $data = [];

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
        if (is_null(self::$Connection)) self::$Connection = new SLiMSConnection;

        $Class = new ReflectionClass($this);
        if (empty($this->Table)) $this->Table = strtolower($Class->getShortName());

        if (is_null(self::$Builder))
            self::$Builder = new Builder(self::$Connection, $this->Table, $this->PrimaryKey);

        return self::$Builder;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->data)) return $this->data[$name];
    }
}
