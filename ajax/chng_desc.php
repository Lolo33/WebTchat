<?php
/**
 * Created by PhpStorm.
 * User: Loïc
 * Date: 12/07/2016
 * Time: 10:44
 */

require_once '../inc/conf.php';
if (isset($_POST['desc'])){
    $desc = htmlspecialchars(trim($_POST['desc']));
    $pseudo = $_POST['psd'];
    if (!empty($desc)){
        $req = $db->prepare('UPDATE membres SET membre_description = :descr WHERE membre_pseudo = :pseudo');
        $req->bindParam(':descr', $desc, PDO::PARAM_STR);
        $req->bindParam(':pseudo', $pseudo, PDO::PARAM_INT);
        $req->execute();
    }
}