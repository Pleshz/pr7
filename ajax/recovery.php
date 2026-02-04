<?php
	session_start();
	include("../settings/connect_datebase.php");
	
	$login = $_POST['login'];
	
	$query_user = $mysqli->query("SELECT * FROM `users` WHERE `login`='".$login."';");
	
	$id = -1;
	if($user_read = $query_user->fetch_row()) {
		$id = $user_read[0];
	}
	
	function PasswordGeneration() {
		$chars="qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP";
		$max=10;
		$size=StrLen($chars)-1;
		$password="";
		
		while($max--) {
			$password.=$chars[rand(0,$size)];
		}
		
		return $password;
	}
	
	if($id != 0) {
		$_SESSION['user'] = $id;
		$ip = $_SERVER['REMOTE_ADDR'];
		$dateStart = date("Y-m-d H:i:s");

		$sql = "INSERT INTO `session`(`IdUser`, `Ip`, `DateStart`, `DateNow`) VALUES ({$id}, '{$ip}', '{$dateStart}', '{$dateStart}')";
		$mysqli->query($sql);

		$sql = "SELECT `Id` FROM `session` WHERE `DateStart` = '{$dateStart}';";
		$query = $mysqli->query($sql);
		$read = $query->fetch_assoc();
		$_SESSION["IdSession"] = $read["Id"];
		
		$sql = "INSERT INTO `logs`(`Ip`, `IdUser`, `Date`, `TimeOnline`, `Event`) VALUES ('{$ip}', {$id}, '{$dateStart}', '00:00:00', 'Пользователь {$login} восстановил пароль')";
		$mysqli->query($sql);
		$password = PasswordGeneration();;
		$query_password = $mysqli->query("SELECT * FROM `users` WHERE `password`= '".md5($password)."';");
		while($password_read = $query_password->fetch_row()) {
			$password = PasswordGeneration();
		}
		$mysqli->query("UPDATE `users` SET `password`='".md5($password)."' WHERE `login` = '".$login."'");
	}
	
	echo $id;
?>