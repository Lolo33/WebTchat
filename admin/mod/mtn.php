<?php
include '../conf.php';
if ($_SESSION['rank'] != 4){
    header('Location: index.php');
}
$req = $db->query('SELECT * FROM retrotalk_settings;');
$req->execute();
$mtn = $req->fetch();

if($mtn['maintenance'] == 'false') {
    if (isset($_POST['submit'])) {
        if (!empty($_POST['reason'])) {
            $reason = htmlspecialchars(trim($_POST['reason']));
            $sql = $db->prepare("UPDATE retrotalk_settings SET maintenance = 'true', reason = :reason");
            $sql->bindValue(':reason', $reason);
            $sql->execute();
            header('Location: ../maintenance.php');
        } else {
            errChmpsVides();
        }
    }
}else{
    if (isset($_POST['submit'])) {
        $sql = $db->query("UPDATE retrotalk_settings SET maintenance = 'false'");
        $sql->execute();
        header('Location: ../maintenance.php');
    }
}
?>