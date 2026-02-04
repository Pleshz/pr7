<?php
session_start();
require_once("../settings/connect_datebase.php");


$sql = "SELECT * FROM `logs` ORDER BY `Date`";
$query = $mysqli->query($sql);

$events = array();
while($read = $query->fetch_assoc()) {
    $status = "";

    $sqlSession = "SELECT * FROM `session` WHERE `IdUser` = {$read["IdUser"]} ORDER BY `DateStart` DESC";
    $querySession = $mysqli->query($sqlSession);
    if($querySession->num_rows > 0) {
        $readSession = $querySession->fetch_assoc();
        $timeEnd = strtotime($readSession["DateNow"]) + 5*60; 
        $timeNow = time();

        if($timeEnd > $timeNow) {
            $status = "online";
        } else {
            $timeEnd = strtotime($readSession["DateNow"]);
            $timeDelta = round(($timeNow - $timeEnd) / 60);
            $status = "Был в сети: {$timeDelta} минут назад";
        }
    }

    $event = array(
        "Id" => $read["Id"],
        "Ip" => $read["Ip"],
        "Date" => $read["Date"],
        "TimeOnline" => $read["TimeOnline"],
        "Status" => $status,
        "Event" => $read["Event"],
    );
    array_push($events, $event);
}

$log_file = __DIR__ . '/logs.txt';
$file_content = "ЖУРНАЛ СОБЫТИЙ\n";

foreach($events as $event) {
    $file_content .= "Дата и время: " . $event['Date'] . "\n";
    $file_content .= "IP пользователя: " . $event['Ip'] . "\n";
    $file_content .= "Время в сети: " . $event['TimeOnline'] . "\n";
    $file_content .= "Статус: " . $event['Status'] . "\n";
    $file_content .= "Событие: " . $event['Event'] . "\n";
    $file_content .= "\n\n";
}

file_put_contents($log_file, $file_content);
$mysqli->close();
?>
