<?php
	session_start();
	include("../settings/connect_datebase.php");
	
	$login = $_POST['login'];
	$password = $_POST['password'];
	
	// ищем пользователя
	$query_user = $mysqli->query("SELECT * FROM `users` WHERE `login`='".$login."'");
	$id = -1;
	
	if($user_read = $query_user->fetch_row()) {
		echo $id;
	} else {
		$mysqli->query("INSERT INTO `users`(`login`, `password`, `roll`) VALUES ('".$login."', '".$password."', 0)");
		
		$query_user = $mysqli->query("SELECT * FROM `users` WHERE `login`='".$login."' AND `password`= '".$password."';");
		$user_new = $query_user->fetch_row();
		$id = $user_new[0];
			
		if($id != -1) $_SESSION['user'] = $id; // запоминаем пользователя

		$ip = $_SERVER['REMOTE_ADDR'];
		$dateStart = date("Y-m-d H:i:s");

		$sql = "INSERT INTO `session`(`IdUser`, `Ip`, `DateStart`, `DateNow`) VALUES ({$id}, '{$ip}', '{$dateStart}', '{$dateStart}')";
		$mysqli->query($sql);

		$sql = "SELECT `Id` FROM `session` WHERE `DateStart` = '{$dateStart}';";
		$query = $mysqli->query($sql);
		$read = $query->fetch_assoc();
		$_SESSION["IdSession"] = $read["Id"];
		
		$sql = "INSERT INTO `logs`(`Ip`, `IdUser`, `Date`, `TimeOnline`, `Event`) VALUES ('{$ip}', {$id}, '{$dateStart}', '00:00:00', 'Пользователь {$login} зарегистрировался')";
		$mysqli->query($sql);
		echo $id;
	}
?>