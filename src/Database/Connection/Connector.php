<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-02-01 21:47:27
 * @modify date 2022-02-01 21:47:27
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Database\Connection;

use PDO;

class Connector
{
    protected $link;
    private $dsn, $username, $password, $options;
    
    public function __construct($Profile)
    {
        $this->dsn = $Profile['dsn'];
        $this->username = $Profile['username'];
        $this->password = $Profile['password'];
        $this->options = $Profile['options'];
        $this->connect();
    }
    
    private function connect()
    {
        $this->link = new PDO($this->dsn, $this->username, $this->password);

        foreach ($this->options as $option) {
            $this->link->setAttribute($option[0],$option[1]);
        }
    }
    
    public function getLink()
    {
        return $this->link;
    }

    public function getDriver()
    {
        return ucfirst(substr($this->dsn, 0, strpos($this->dsn, ':')));
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