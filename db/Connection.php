<?php

namespace db;

use PDO;

Connection::getConnection();

class Connection {

    /**
     * Database Connect Object
     * @return PDO
     */
    public static function getConnection() {
        $params = require($_SERVER['DOCUMENT_ROOT'] . '/db/db_params.php');
        $dsn = "mysql:host={$params['host']};dbname={$params['dbname']}";
        $db = new PDO($dsn, $params['user'], $params['pass']);
        return $db;
    }
}

