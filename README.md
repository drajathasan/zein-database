# Zein/Database

PHP library to generate mysql database query. Inspired from Eloquent.

### How to install
```SHELL 
composer require zein/database
```

### How to use
#### # Parent Model
Make a parent model like this:

```PHP
<?php

namespace App\Models;

use PDO;
use Zein\Database\Dages\ModelContract;
use Zein\Database\Connection\Driver\Mysql\Dsn;

class ParentModel extends ModelContract
{
    use Dsn;

    protected function createConnectionInit()
    {
        $this->ConnectionProfile = [
            'dsn' => Dsn::init(['host' => 'DatabaseHost', 'port' => 'DatabasePort', 'dbname' => 'DatabaseName']),
            'username' => 'DatabaseUsername',
            'password' => 'Database Password',
            'options' => [
                [PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION)]
            ]
        ];
    }
}
```
#### # Model
```PHP
<?php
namespace App\Models;

// Mendoan.php and ParentModel.php in same directory
class Mendoan extends ParentModel
{
    /**
     * custom table name
     * if your table is not same with this object name
     *
     * Default Table : Mendoan
     **/
     // protected $Table = ''; // fill it your table name
     
    /**
     * custom PrimaryKey column
     * 
     * Default column : id
     **/
     // protected $PrimaryKey = '';
}
```

#### # Select statement
```PHP
<?php

use App\Models\Mendoan;

// Short Hand way - retrive all column based primarykey
$Mendoan = Mendoan::find(1);

// Spesific column
$Mendoan = Mendoan::select('ingredients','cooked_at','expired_at')
                      ->get();

// With criteria
$Mendoan = Mendoan::select('ingredients','cooked_at','expired_at')
                      ->where('beangrade', 'good')
                      ->get();
                      
// With join
$Mendoan = Mendoan::select('ingredients','cooked_at','expired_at')
                      ->from('mendoan AS m')
                      ->innerJoin('bean AS b', ['b.grade', '=', 'm.beangrade'])
                      ->where('m.beangrade', 'good')
                      ->get();
```

#### # Insert statement
```PHP
<?php

use App\Models\Mendoan;

// Short Hand way - Set column as data and save it
$Mendoan = new Mendoan; // create instance first

// set the column as $Mendoan property
$Mendoan->ingredients = 'Bean, Yeast';

// call save method to insert data
$Mendoan->save();

// or use create method
$Mendoan = Mendoan::create(['ingredients' => 'Bean, Yeast']);

```


                      


