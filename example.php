<?php

require_once 'autoload.php';

use \Implant\Ioc;

/**
 * Database connection.
 */
class Database {

    /**
     * The PHP Data object used to handle queries.
     *
     * @var \PDO
     */
    private $pdo;

    /**
     * Name of the database.
     *
     * @var string
     */
    private $name;

    /**
     * Constructor.
     *
     * @param \PDO $pdo Intance of \PDO.
     * @param string $name Name of the database.
     */
    public function __construct(\PDO $pdo, string $name) {
        $this->pdo = $pdo;
        $this->name = $name;
    }

    /**
     * Execute a query.
     *
     * @param string $sql The query string to execute.
     */
    public function query(string $sql): void {
        echo 'run query on ' . $this->name . PHP_EOL;
        echo $sql . PHP_EOL;
    }
}

class DatabaseManager {

    /**
     * Database connections
     *
     * @var array
     */
    private $databases;


    /**
     * Delegate method which creates a PHP data object.
     *
     * @var callable
     */
    private $pdoDelegate;

    /**
     * Delegate method which creates a Database instance.
     *
     * @var callable
     */
    private $databaseDelegate;

    /**
     * Constructor
     *
     * @var callable $pdoDelegate
     * @var callable $databaseDelegate
     */
    public function __construct(callable $pdoDelegate, callable $databaseDelegate) {
        $this->databases = [];
        $this->databaseDelegate = $databaseDelegate;
        $this->pdoDelegate = $pdoDelegate;
    }

    /**
     * Get a database connection. Creates a new one if the
     * database does not yet exist.
     *
     * @param string $name Name of the database.
     * @return Database $database The database instance.
     */
    public function getDatabase(string $name): Database {
        if (!array_key_exists($name, $this->databases)) {
            $pdo = ($this->pdoDelegate)($name);
            $this->databases[$name] = ($this->databaseDelegate)($pdo, $name);
        }
        return $this->databases[$name];
    }
}

function main() {

    $databases = [
        'default' => [
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'dbname'    => 'defaultdb',
            'username'  => 'root',
            'password'  => 'secret',
        ],
        'someDb' => [
            'driver'    => 'dblib',
            'host'      => 'somehost.com',
            'dbname'    => 'somedb',
            'username'  => 'someuser',
            'password'  => 'somepasswd',
        ],
    ];


    $ioc = new Ioc\Container();

    $ioc->register('pdo', new Ioc\FactoryService('\PDO', $databases))
        ->bindParam('dsn',
            new Ioc\TemplateParam('@driver:host=@host;dbname=@dbname'))
        ->bindParam('username',
            new Ioc\TemplateParam('@username'))
        ->bindParam('passwd',
            new Ioc\TemplateParam('@password'));

    $ioc->register('database', new Ioc\FactoryService('Database'));

    $ioc->register('databaseManager', new Ioc\Service('DatabaseManager'))
        ->bindParam('pdoDelegate', new Ioc\ResolveParam('pdo'))
        ->bindParam('databaseDelegate',  new Ioc\ResolveParam('database'));

    $databaseManager = $ioc->resolve('databaseManager');
    $database = $databaseManager->getDatabase('someDb');
    $database->query('SELECT * FROM T_GROUP');
}
main();
