<?php

namespace server;

require $_SERVER['DOCUMENT_ROOT'] . '/db/Connection.php';
require $_SERVER['DOCUMENT_ROOT'] . '/db/Migration.php';

use db\Connection;
use db\Migration;
use PDO;


class Controller {

    public function timerInit() {
        $db = Connection::getConnection();
        $sql = "SELECT seconds, pause FROM timer WHERE id = 1";
        $res = $db->prepare($sql);
        $res->setFetchMode(PDO::FETCH_ASSOC);
        $res->execute();
        $row = $res->fetch();
        return json_encode(['seconds' => $row['seconds'], 'pause' => $row['pause']]);
    }

    public function startTimer($seconds) {
        $db = Connection::getConnection();
        Migration::create_timer_table($db);
        $sql = "UPDATE timer SET seconds = $seconds, pause = 0";
        $db->exec($sql);
    }

    public function pauseTimer($seconds) {
        $db = Connection::getConnection();
        $sql = "UPDATE timer SET pause = $seconds";
        $db->exec($sql);
    }

    public function resetTimer() {
        $db = Connection::getConnection();
        $sql = "UPDATE timer SET seconds = 0, pause = 0";
        $db->exec($sql);
    }

    public function index() {
        $db = Connection::getConnection();
        $messages = $this->selectAll($db);
        return json_encode($messages);
    }

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
     * Retrieve all rows from Db table in specified order
     * @param PDO object of db connection
     * @param bool true adds the ascending order into query
     * @return array objects $this model on success filled with db row, or null
     */
    private function selectAll($db, $order = true) {
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




