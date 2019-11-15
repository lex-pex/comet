<?php

namespace server;

require $_SERVER['DOCUMENT_ROOT'] . '/db/Connection.php';
require $_SERVER['DOCUMENT_ROOT'] . '/db/Migration.php';

use db\Connection;
use db\Migration;
use PDO;


class Controller {

    /* ___ Timer Controller  */

    /**
     * Initiating the Timer Plug-In with existing seconds amount on the server
     * @return string - amount of seconds, pause of seconds if it applied
     */
    public function timerInit() {
        $db = Connection::getConnection();
        $sql = "SELECT seconds, pause FROM timer WHERE id = 1";
        $res = $db->prepare($sql);
        $res->setFetchMode(PDO::FETCH_ASSOC);
        $res->execute();
        $row = $res->fetch();
        return json_encode(['seconds' => $row['seconds'], 'pause' => $row['pause']]);
    }

    /**
     * Fixate the time of start timer into table
     * @param $seconds
     */
    public function startTimer($seconds) {
        $db = Connection::getConnection();
        Migration::create_timer_table($db);
        $sql = "UPDATE timer SET seconds = $seconds, pause = 0";
        $db->exec($sql);
    }

    /**
     * Fixate the time of pause timer into table
     * @param $seconds
     */
    public function pauseTimer($seconds) {
        $db = Connection::getConnection();
        $sql = "UPDATE timer SET pause = $seconds";
        $db->exec($sql);
    }

    /**
     * Reset the start and pause counter
     */
    public function resetTimer() {
        $db = Connection::getConnection();
        $sql = "UPDATE timer SET seconds = 0, pause = 0";
        $db->exec($sql);
    }

    /* ___ Chat Controller  */

    /**
     * Initiating the Chat Plug-In with existing messages
     * @return string
     */
    public function index() {
        $db = Connection::getConnection();
        $messages = $this->selectAllMessages($db);
        return json_encode($messages);
    }

    /**
     * persist the new message into base and return to the view
     * @param $data array with sender name and message text
     * @return string ip-address to pass it in the view
     */
    public function store($data) {
        $db = Connection::getConnection();
        Migration::create_messages_table($db);
        $t = 'messages';
        $f = ['name', 'message', 'ip', 'created_at'];
        $ip = $this->getUserIpAddress();
        $a = [$data['name'], $data['message'], $ip, date('Y-m-d G:i:s')];
        $this->insert($db, $t, $f, $a);
        return $ip;
    }

    /**
     * Plane insert operation into specified table by certain connection
     * @param $db - the prepared PDO object
     * @param $table - target table
     * @param array $fields - variables of the record
     * @param array $a - array with values
     * @return int - primarty id of the record or zero on fail
     */
    private function insert($db, $table, array $fields, array $a = []) {
        $names = '';
        $wildcards = '';
        foreach ($fields as $n => $f)
            if(is_numeric($n)) {
                $names .= "$f, ";
                $wildcards .= ":$f, ";
            }
        $names = trim($names, ', ');              // field, field ...
        $wildcards = trim($wildcards, ', ');      // :field, :field ...
        $query = "INSERT INTO $table ($names) VALUES ($wildcards)";
        $res = $db->prepare($query);
        foreach ($fields as $n => $f)
            if(is_numeric($n))
                $res->bindParam(":$f", $a[$n]);
        if ($res->execute()) {
            return $db->lastInsertId();
        }
        return 0;
    }

    /**
     * Receive ip-address of the current app user
     * @return string as remote or local address
     */
    private function getUserIpAddress() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP'); // ip from share internet
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR'); // ip pass from proxy
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    /**
     * Retrieve all rows from Messages table in specified order
     * @param PDO object of db connection
     * @param bool true adds the ascending order into query
     * @return array objects $this model on success filled with db row, or null
     */
    private function selectAllMessages($db, $order = true) {
        $order = $order ? 'asc' : 'desc';
        $query = "SELECT * FROM messages ORDER BY id $order";
        $res = $db->prepare($query);
        $res->setFetchMode(PDO::FETCH_ASSOC);
        $res->execute();
        $i = 0;
        $resultSet = [];
        while ($row = $res->fetch()) {
            $resultSet[$i] = $row;
            $i++;
        }
        return $resultSet;
    }
}




