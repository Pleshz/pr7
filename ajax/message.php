<?
    session_start();
	include("../settings/connect_datebase.php");

    $IdUser = $_SESSION['user'];
    $Message = $_POST["Message"];
    $IdPost = $_POST["IdPost"];
    $idSession = $_SESSION["IdSession"];

    $mysqli->query("INSERT INTO `comments`(`IdUser`, `IdPost`, `Messages`) VALUES ({$IdUser}, {$IdPost}, '{$Message}');");

    $sql = "SELECT `session`.*, `users`.`login` FROM `session` `session` JOIN `users` `users` ON `users`.`id` = `session`.`IdUser` WHERE `session`.`Id` = {$idSession}";

	$query = $mysqli->query($sql);
	$read = $query->fetch_array();

	$timeStart = strtotime($read["DateStart"]);
	$timeNow = time();
	$ip = $read["Ip"];
	$timeDelta = gmdate("H:i:s", ($timeNow - $timeStart));
	$date = date("Y-m-d H:i:s");
	$login = $read["login"];

	$sql = "INSERT INTO `logs`(`Ip`, `IdUser`, `Date`, `TimeOnline`, `Event`) VALUES ('{$ip}', {$IdUser},'{$date}','{$timeDelta}','Пользователь {$login} оставил комментарий к записи [Id: {$IdPost}]: {$Message}')";
	$mysqli->query($sql);

?>