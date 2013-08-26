<?php

class DB
{
    private static $handler;
    private static $instance;
    private $dbname;

    public function getDbname()
    {
        return $this->dbname;
    }

    private function __construct($params)
    {

        self::$handler = mysql_connect($params['host'], $params['user'], $params['password']);
        $this->dbname = $params['database'];

    }

    private function __clone()
    {
    }

    public static function init($params = array())
    {
        if (self::$instance == null) {
            try {
                self::$instance = new DB($params);
            } catch (ErrorException $e) {
                throw new \RuntimeException('Access denied!');
            }
        }
        if (!self::$instance) {
            throw new \RuntimeException('Could not connect!');
        }
        if (!mysql_select_db($params['database'], self::$handler)) {
            throw new \RuntimeException("Database {$params['database']} not found!");
        }
        return self::$instance;
    }

    public function query($sql)
    {
        return mysql_query($sql);
    }

    public function fetchArray($set, $index = MYSQL_NUM)
    {
        return mysql_fetch_array($set, $index);
    }

    public function fetchAssoc($set)
    {
        return mysql_fetch_assoc($set);
    }

    public function escape($sql)
    {
        return mysql_real_escape_string(htmlspecialchars($sql));
    }
}
