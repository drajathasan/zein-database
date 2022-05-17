<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-02-01 22:23:08
 * @modify date 2022-05-17 22:37:56
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Database\Query;

use PDO;
use PDOException;
use Zein\Database\Utils;

class Builder
{
    use Utils,
        Compose,
        Join,
        Alias,
        Error,
        Limit,
        Reset;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private $Connection;

    /**
     * Model namespace
     */
    private $Model;

    /**
     * Separator
     *
     * @var string
     */
    public $Separator = '`';

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
    private $WhereType = 'where';
    private $AllowableOperator = ['>=','<=','>','<','<>','IS NOT','NOT','IS','=','!='];

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
    private $MarkType = 'question';

    /**
     * Join
     */
    private $Join = [];

    /**
     * Order and Group
     */
    private $OrderBy = '';
    private $GroupBy = '';

    /**
     * Data exists
     */
    public $Exists = false;

    /**
     * Limit and offset
     */
    private $Limit = 0;
    private $Offset = ''; 

    /**
     * Timestamp
     */
    private $Dateformat = 'Y-m-d H:i:s';
    private $Created_at = 'created_at';
    private $Updated_at = 'updated_at';


    /**
     * Error query processing
     */
    private $Error = [];

    /**
     * Query Builder contructor
     */
    public function __construct(array $Property)
    {
        foreach ($Property as $property => $value) {
            $this->$property = $value;
        }
    }

    public function select():Builder
    {
        $this->State = 'select';

        if (func_num_args() > 0 && func_get_args()[0] !== '*')
            $this->Column = $this->setColumnSeparator(func_get_args());

        return $this;
    }

    public function from(string $TableName):Builder
    {
        $this->Table = $this->setSeparator($TableName);
        return $this;
    }
 
    public function where():Builder
    {
        $Arguments = func_get_args();

        switch (func_num_args()) {
            case 3:
                $this->Criteria = array_merge($this->Criteria, [$Arguments]);
                break;
            
            default:
                $this->Criteria[$Arguments[0]] = $Arguments[1];
                break;
        }
        return $this;
    }

    public function whereIn(string $Column, array $Data):Builder
    {
        $this->Criteria = array_merge($this->Criteria, [$Column => $Data]);
        return $this;
    }

    public function groupBy($Column)
    {
        $this->GroupBy = (is_array($Column)) ? implode(',', $Column) : $Column;
        return $this;
    }

    public function orderBy($Column, string $OrderType = ''):Builder
    {
        if (is_callable($Column))
        {
            $this->OrderBy = trim($Column());
        }
        else
        {
            $this->OrderBy = trim($this->setSeparator($Column) . ' ' . $this->cleanHarmCharacter($OrderType));
        }

        return $this;
    }

    public function limit(int $Limit, $Offset = ''):Builder
    {
        $this->Limit = $Limit;
        if (is_numeric($Offset)) $this->Offset = $Offset;

        return $this;
    }

    public function setMarkType(string $MarkTypeName):Builder
    {
        $this->MarkType = $MarkTypeName;
        return $this;
    }

    public function dump(bool $Detail = false)
    {
        if (!$Detail) return trim($this->result() . PHP_EOL);

        return [
            'query' => trim($this->result()),
            'execute' => $this->Criteria
        ];
    }

    public function update(array $Data, bool $Debug = false)
    {
        $this->State = 'update';
        $this->Data = $Data;
        
        try {
            $State = $this
                        ->Connection
                        ->getLink()
                        ->prepare(trim($this->result()));
            $State->execute($this->Criteria);

            return $State->rowCount();

        } catch (PDOException $e) {
            $this->setError($e);
            if ($Debug) return $this->Error;
        }
    }
    
    public function insert(array $Data, bool $Debug = false)
    {
        $this->State = 'insert';
        $this->Data = $Data;

        try {
            $Link = $this->Connection->getLink();
            $State = $Link->prepare(trim($this->result()));
            $State->execute($this->Criteria);

            return $Link->lastInsertId();

        } catch (PDOException $e) {
            $this->setError($e);
            if ($Debug) return $this->Error;
        }
    }

    public function delete()
    {
        $this->State = 'delete';

        try {
            $State = $this
                        ->Connection
                        ->getLink()
                        ->prepare(trim($this->result()));
            $State->execute($this->Criteria);

            return $State->rowCount();

        } catch (PDOException $e) {
            $this->setError($e);
            if ($Debug) return $this->Error;
        }
    }

    public function count(bool $Debug = false)
    {
        $this->Column = 'COUNT(' . $this->PrimaryKey . ')';

        try {
            $State = $this
                        ->Connection
                        ->getLink()
                        ->prepare(trim($this->result()));
            $State->execute($this->Criteria);

            return (int)@$State->fetch(PDO::FETCH_NUM)[0]??0;

        } catch (PDOException $e) {
            $this->setError($e);
            if ($Debug) return $this->Error;
        }
    }

    public function get(bool $Debug = false)
    {
        try {
            
            $State = $this
                        ->Connection
                        ->getLink()
                        ->prepare(trim($this->result()));

            $State->execute($this->Criteria);

            if ($State->rowCount() === 1) 
            {
                $this->Exists = true;
                return $this->single($State);
            }

            if ($State->rowCount() > 1)
            {
                $this->Exists = true;
                return $this->many($State);
            }

        } catch (PDOException $e) {
            $this->setError($e);
            if ($Debug) return $this->Error;
        }
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->Data)) return $this->Data[$name];

        if (property_exists($this, $name)) return $this->$name;
    }
}