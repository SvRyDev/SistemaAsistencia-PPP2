<?php
class Database
{
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh;
    private $error;

    public function __construct()
    {
        // Set DSN
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        // Set options
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );

        // Create a new PDO instance
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }

    // Query method
    public function query($query)
    {
        return $this->dbh->query($query);
    }

    // Prepare method
    public function prepare($query)
    {
        return $this->dbh->prepare($query);
    }

    // Execute method
    public function execute($stmt)
    {
        return $stmt->execute();
    }

    // Get result set as array of objects
    public function resultSet($stmt)
    {
        $this->execute($stmt);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Get single record as object
    public function single($stmt)
    {
        $this->execute($stmt);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Get row count
    public function rowCount($stmt)
    {
        return $stmt->rowCount();
    }

    // Get the last inserted ID
    public function lastInsertId()
    {
        return $this->dbh->lastInsertId();
    }
}
