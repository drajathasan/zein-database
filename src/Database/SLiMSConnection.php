<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-02-01 21:47:27
 * @modify date 2022-02-01 21:47:27
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Database;

use PDO;

class SLiMSConnection
{
    protected $link;
    private $dsn, $username, $password, $options;
    
    public function __construct($options = [])
    {
        $this->dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
        $this->username = DB_USERNAME;
        $this->password = DB_PASSWORD;
        $this->options = $options;
        $this->connect();
    }
    
    private function connect()
    {
        $this->link = new PDO($this->dsn, $this->username, $this->password, $this->options);
    }
    
    public function getLink()
    {
        return $this->link;
    }

    public function __sleep()
    {
        return array('dsn', 'username', 'password');
    }
    
    public function __wakeup()
    {
        $this->connect();
    }
}