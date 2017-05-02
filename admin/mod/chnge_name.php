<?php
/**
 * Created by PhpStorm.
 * User: Lolynx
 * Date: 21/03/2017
 * Time: 15:17
 */

include "../conf.php";

if (isset($_POST["nom"])){
    $nom = htmlspecialchars(trim($_POST["nom"]));
    if (!empty($nom)){
        $req = $db->prepare("UPDATE retrotalk_settings SET nom = :nom;");
        $req->bindParam(":nom", $nom, PDO::PARAM_STR);
        $req->execute();
        header("Location: ../index.php");
    }else{
        echo '<script>alert("Erreur: Le champ nom est vide !");document.location.replace("../index.php");</script>';
    }
}else{
    header("Location: ../index.php");
}