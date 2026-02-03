<?
    if(isset($_SESSION["IdSession"])) {
        $idSession = $_SESSION["IdSession"];

        $dateNow = date("Y-m-d H:i:s");
        $sql = "UPDATE `session` SET `DateNow`='{$dateNow}' WHERE `Id` = {$idSession}";
        $mysqli->query($sql);
    }
?>