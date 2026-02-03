<?php
	session_start();
	require_once("../settings/connect_datebase.php");

	$idUser = $_SESSION["user"];
	$idSession = $_SESSION["IdSession"];

	$sql = "SELECT `session`.*, `users`.`login` FROM `session` `session` JOIN `users` `users` ON `users`.`id` = `session`.`IdUser` WHERE `session`.`Id` = {$idSession}";

	$query = $mysqli->query($sql);
	$read = $query->fetch_array();

	$timeStart = strtotime($read["DateStart"]);
	$timeNow = time();
	$ip = $read["Ip"];
	$timeDelta = gmdate("H:i:s", ($timeNow - $timeStart));
	$date = date("Y-m-d H:i:s");
	$login = $read["login"];

	$sql = "INSERT INTO `logs`(`Ip`, `IdUser`, `Date`, `TimeOnline`, `Event`) VALUES ('{$ip}', {$idUser},'{$date}','{$timeDelta}','Пользователь {$login} вышел')";
	$mysqli->query($sql);

	session_destroy();
?>