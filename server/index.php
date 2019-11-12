<?php

if(!$action = $_POST['action'])
    exit();

include $_SERVER['DOCUMENT_ROOT'] . '/server/Controller.php';
$c = new server\Controller();

switch($action) {
    case 'index':
        echo $c->index();
        break;
    case 'store':
        echo store($c);
        break;
    case 'timer_start':
        echo timer_start($c);
        break;
    case 'timer_init':
        echo timer_init($c);
        break;
    case 'timer_pause':
        echo timer_pause($c);
        break;
    case 'timer_reset':
        echo timer_reset($c);
        break;
    default:
        throw new Exception('There is not such an action');
}

function store($c) {
    $data = [
        'name' => $_POST['name'],
        'message' => $_POST['message']
    ];
    return $c->store($data);
}

function timer_init($c) {
    return $c->timerInit();
}

function timer_start($c) {
    $seconds = $_POST['start_seconds'];
    return $c->startTimer($seconds);
}

function timer_pause($c) {
    $seconds = $_POST['seconds'];
    return $c->pauseTimer($seconds);
}

function timer_reset($c) {
    return $c->resetTimer();
}












