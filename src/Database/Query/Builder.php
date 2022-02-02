<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-02-01 22:23:08
 * @modify date 2022-02-01 22:23:08
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Database\Query;

use Zein\Database\Utils;

class Builder
{
    use Utils,Compose;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private $Connection;

    /**
     * Undocumented variable
     *
     * @var mix
     */
    private $Column = '*';

    /**
     * Undocumented variable
     *
     * @var string
     */
    private $Table = '';

    /**
     * Undocumented variable
     *
     * @var array
     */
    private $Criteria = [];

    /**
     * Undocumented variable
     *
     * @var string
     */
    private $State = 'select';

    /**
     * Primary Key
     */
    private $PrimaryKey = 'id';

    /**
     * Undocumented variable
     *
     * @var array
     */
    private $Data = [];

    /**
     * Undocumented variable
     *
     * @var string
     */
    private $MarkType = 'named';


    /**
     * Query Builder contructor
     */
    public function __construct($Connection, string $tablename, string $PrimaryKey)
    {
        $this->Connection = $Connection;
        $this->Table = $tablename;
        $this->PrimaryKey = $PrimaryKey;
    }

    public function select():Builder
    {
        $this->State = 'select';

        if (func_num_args() > 0 && func_get_args()[0] !== '*')
            $this->Column = $this->setColumnSeparator(func_get_args(), '`');

        return $this;
    }

    public function from(string $TableName):Builder
    {
        $this->Table = $this->setSeparator($TableName, '`');
        return $this;
    }

    public function where():Builder
    {
        $Arguments = func_get_args();

        switch (func_num_args()) {
            case 1:
                $this->Criteria = $Arguments[0];
                break;
            
            default:
                $this->Criteria[$Arguments[0]] = $Arguments[1];
                break;
        }
        return $this;
    }

    public function get()
    {
        $Link = $this->Connection->getLink();
        $State = $Link->prepare($this->result());
        $State->execute($this->Criteria);

        if ($State->rowCount() === 1) $this->Data = $State->fetch(\PDO::FETCH_ASSOC);

        if ($State->rowCount() > 1) return $this->many($State);

        return $this;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->Data)) return $this->Data[$name];

        if (property_exists($this, $name)) return $this->$name;
    }
}