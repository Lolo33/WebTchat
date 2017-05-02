<?php
/**
 * Created by PhpStorm.
 * User: Loïc
 * Date: 12/07/2016
 * Time: 03:49
 */
require_once '../inc/conf.php';

if (isset($_POST['imgid'])){
    $id = $_POST['imgid'];
    $pseudo = $_POST['psd'];
    if (!empty($id)){
        if($_POST['psd'] == $_SESSION['pseudo'] || $_SESSION['rank'] >= 3) {
            $req = $db->prepare('UPDATE membres SET membre_avatar = :idav WHERE membre_pseudo = :pseudomembre');
            $req->bindParam(':idav', $id, PDO::PARAM_INT);
            $req->bindParam(':pseudomembre', $pseudo, PDO::PARAM_STR);
            $req->execute();
        }
    }
}