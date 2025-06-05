<?php

require_once 'constants.php';

/**
 * DataBase class
 * Provides a static interface to manage a PDO connection to a MySQL database,
 * as well as utility methods for transactions and SQL queries.
 */
class DataBase
{
    // Singleton instance of the PDO connection
    private static ?PDO $db = null;

    /**
     * Returns a single instance of the PDO connection.
     * Initializes the connection if it hasn't been created yet.
     *
     * @return PDO PDO instance connected to the database
     */
    public static function db(): PDO
    {
        if (self::$db === null) {
            try {
                // Create the PDO object using constants defined in constants.php
                $db = new PDO(
                    "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
                    DB_USER,
                    DB_PASS
                );

                // Enable exceptions for PDO errors
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // Disable emulation of prepared statements
                $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            } catch (Throwable $th) {
                // If connection fails, send HTTP 500 and stop the script
                http_response_code(500);
                die("Cannot connect to database");
            }

            self::$db = $db;
        }

        return self::$db;
    }

    /**
     * Executes a prepared SQL query with the given parameters.
     *
     * @param string $sql   The SQL query to execute
     * @param array $param  The parameters associated with the query
     * @return PDOStatement The resulting statement object
     */
    public static function dbRun(string $sql, array $param = []): PDOStatement
    {
        $statement = self::db()->prepare(query: $sql);
        $statement->execute($param);
        return $statement;
    }

    /**
     * Starts a transaction.
     *
     * @return bool True if the transaction begins successfully
     */
    public static function begin()
    {
        return self::db()->beginTransaction();
    }

    /**
     * Commits the current transaction.
     *
     * @return bool True if the commit is successful
     */
    public static function commit()
    {
        return self::db()->commit();
    }

    /**
     * Rolls back the current transaction.
     *
     * @return bool True if the rollback is successful
     */
    public static function rollback()
    {
        return self::db()->rollBack();
    }

    /**
     * Returns the ID of the last inserted record in the database.
     *
     * @return string The ID of the last insertion
     */
    public static function lastInsertId()
    {
        return self::db()->lastInsertId();
    }
}
?>
