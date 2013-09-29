<?php

class Sql {

    /**
     * Instance of PDO
     * @var \PDO 
     */
    private static $_instance;

    /**
     * This static function returns instance of PDO
     * @author Lauri Orgla
     * @version 1.0
     * @return type
     */
    public static function getDB() {
        if (!self::$_instance) {
            self::$_instance = new PDO(
                    sprintf('mysql:host=%s;dbname=%s;', Config::get('mysql_host'), Config::get('mysql_database')), Config::get('mysql_username'), Config::get('mysql_password')
            );
            self::query("SET NAMES utf8");
        }
        return self::$_instance;
    }

    /**
     * fetchAll returns all rows from database
     * @author Lauri Orgla
     * @version 1.0
     * @param string $sql
     * @param mized $fields
     * @return object
     */
    public static function fetchAll($sql, $fields = array()) {
        $stmt = self::getDB()->prepare($sql);
        $stmt->execute($fields);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * fetch fetches only one row from database
     * @author Lauri Orgla
     * @version 1.0
     * @param string $sql
     * @param mixed $fields
     * @return type
     */
    public static function fetch($sql, $fields = array()) {
        $stmt = self::getDB()->prepare($sql);
        $stmt->execute($fields);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * smartFetch will use query parameters to limit result or do any other action
     * @author Lauri Orgla
     * @version 1.0
     * @param string $sql
     * @param mixed $fields
     * @return type
     */
    public static function smartFetch($sql, $fields = array()) {
        //Limit and start
        //if only limit used then it limits output
        //if start is used too, then it defines number of rows to skip
        $query_limit = '';
        if (Request::query('limit')) {
            if (Request::query('start')) {
                $query_limit = sprintf(' LIMIT %d, %d', Request::query('start'), Request::query('limit'));
            } else {
                $query_limit = sprintf(' LIMIT %d', Request::query('limit'));
            }
        }
        return self::fetch($sql . $query_limit, $fields);
    }

    /**
     * smartFetch will use query parameters to limit result or do any other action
     * @author Lauri Orgla
     * @version 1.0
     * @param string $sql
     * @param mixed $fields
     * @return type
     */
    public static function smartFetchAll($sql, $fields = array()) {
        //Limit and start
        //if only limit used then it limits output
        //if start is used too, then it defines number of rows to skip
        $query_limit = '';
        if (Request::query('limit')) {
            if (Request::query('start')) {
                $query_limit = sprintf(' LIMIT %d, %d', Request::query('start'), Request::query('limit'));
            } else {
                $query_limit = sprintf(' LIMIT %d', Request::query('limit'));
            }
        }
        return self::fetchAll($sql . $query_limit, $fields);
    }

    /**
     * returns the ID of last row inserted to database
     * @author Lauri Orgla
     * @version 1.0
     * @return type
     */
    public static function getLastInsertId() {
        return self::getDB()->lastInsertId();
    }

    /**
     * Begins PDO transaction
     * @author Lauri Orgla
     * @version 1.0
     * @return type
     */
    public static function beginTransaction() {
        return self::getDB()->beginTransaction();
    }

    /**
     * executes rollBack on PDO
     * @author Lauri Orgla
     * @version 1.0
     * @return type
     */
    public static function rollBack() {
        return self::getDB()->rollback();
    }

    /**
     * Commits PDO transaction to database server
     * @author Lauri Orgla
     * @version 1.0
     * @return type
     */
    public static function commit() {
        return self::getDB()->commit();
    }

    /**
     * Executes query to database server without returning any results. always returns true
     * @author Lauri Orgla
     * @version 1.0
     * @param string $sql
     * @param mixed $fields
     * @return boolean
     */
    public static function query($sql, $fields = array()) {
        self::fetch($sql, $fields);
        return true;
    }
    
    /**
     * todo::
     * insertObject
     * updateObject
     */
}

?>