<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-02-02 13:53:37
 * @modify date 2022-05-17 21:26:15
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Database\Dages;


use Countable,JsonSerializable,ReflectionClass,PDO,Traversable;
use Zein\Database\Query\Builder;
use Zein\Database\Connection\Connector;
use Zein\Database\Connection\Driver\Mysql\Dsn;

abstract class SLiMSModelContract implements JsonSerializable,Countable
{
    use Arrayable,Dsn,Magic,Shorthand;

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
    protected $Table = '';

    /**
     * Timestamp
     */
    protected $Timestamp = true;
    protected $Dateformat = 'Y-m-d H:i:s';
    protected $Created_at = 'created_at';
    protected $Updated_at = 'updated_at';

    /**
     * 
     */
    protected $Data = [];

    protected function createConnectionInit() {}

    private function builder()
    {
        if (is_null(self::$Connection)) 
        {
            if (!defined('ENVIRONMENT')) define('ENVIRONMENT', 'development');

            self::$Connection = new Connector([
                'dsn' => self::init(['host' => DB_HOST, 'port' => DB_PORT, 'dbname' => DB_NAME]),
                'username' => DB_USERNAME,
                'password' => DB_PASSWORD,
                'options' => [[PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION]]
            ]);
        }

        $Class = new ReflectionClass($this);

        // get property
        $Property = get_class_vars(get_class($this));
        $Property['Model'] = $Class->getName();
        if (empty($Property['Table']))
        {
            $this->Table = strtolower($Class->getShortName());
            $Property['Table'] = strtolower($Class->getShortName());
        }
        
        $Property['Connection'] = self::$Connection;
        unset($Property['Builder']);
        unset($Property['Data']);

        self::$Builder = new Builder($Property);

        return self::$Builder;
    }

    public function removeLink()
    {
        unset($this->ConnectionProfile);
    }

    public function getConnection()
    {
        return self::$Connection;
    }

    public function getBuilder()
    {
        return self::$Builder;
    }   
}
