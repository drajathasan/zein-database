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


    public function __construct(\Zein\Database\Connection $Connection, string $tablename)
    {
        $this->Connection = $Connection;
        $this->Table = $tablename;
    }

    public function select():Builder
    {
        $this->State = 'select';

        if (func_num_args() > 1)
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

        if ($State->rowCount()) $this->Data = $State->fetch(\PDO::FETCH_ASSOC);

        return $this;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->Data)) return $this->Data[$name];

        if (property_exists($this, $name)) return $this->$name;
    }
}