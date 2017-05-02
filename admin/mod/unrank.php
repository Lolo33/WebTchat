<?php
/**
 * Created by PhpStorm.
 * User: Loïc
 * Date: 09/07/2016
 * Time: 05:32
 */

include '../conf.php';
if ($_SESSION['rank'] != 4){
    header('Location: ../index.php');
}
if (isset($_POST['psd'])){
    if (!empty($_POST['psd'])){
        $pseudo = htmlspecialchars(trim($_POST['psd']));
        $requete = $db->prepare('UPDATE membres SET membre_rank = 1 WHERE membre_pseudo = :pseudo');
        $requete->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
        $requete->execute();
        header('Location: ../gest_staff.php');
    }else{
        errChmpsVides();
    }
}else{
    errFormulaire();
}