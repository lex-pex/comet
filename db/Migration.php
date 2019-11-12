<?php

namespace db;

class Migration {

    /**
     * Create Messages Table for current application
     */
    public static function create_messages_table($db) {
        $sql =  "CREATE TABLE IF NOT EXISTS messages (
                 id INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
                 name VARCHAR( 70 ) NOT NULL,
                 message VARCHAR( 250 ) NOT NULL,
                 ip VARCHAR( 50 ) NOT NULL,
                 created_at TIMESTAMP NULL,
                 updated_at TIMESTAMP NULL);";
        $db->exec($sql);
    }

    /**
     * Create Timer Table for persist time in seconds
     */
    public static function create_timer_table($db) {
        $sql =  "CREATE TABLE IF NOT EXISTS timer (id INT(1) AUTO_INCREMENT PRIMARY KEY, seconds INT(11) NOT NULL, pause INT(11) NOT NULL);
        INSERT INTO timer(id, seconds, pause) VALUES (1, 0, 0);";
        $db->exec($sql);
    }

}

