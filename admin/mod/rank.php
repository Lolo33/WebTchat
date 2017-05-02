<?php
/**
 * Created by PhpStorm.
 * User: Loïc
 * Date: 09/07/2016
 * Time: 05:12
 */

include '../conf.php';
if ($_SESSION['rank'] < 4){
    header('Location: ../index.php');
}
if (isset($_POST['subm'])){
    if (!empty($_POST['pseudo'])){
        $pseudo = htmlspecialchars(trim($_POST['pseudo']));
        $rank = htmlspecialchars(trim($_POST['rank']));
        $req_membre = $db->prepare('SELECT * FROM membres WHERE membre_pseudo = :pseudo');
        $req_membre->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
        $req_membre->execute();
        $membre = $req_membre->fetch();
        if ($membre != NULL) {
            $requete = $db->prepare('UPDATE membres SET membre_rank = :rank WHERE membre_pseudo = :pseudo');
            $requete->bindParam(':rank', $rank, PDO::PARAM_INT);
            $requete->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
            $requete->execute();
            header('Location: ../gest_staff.php');
        }else{
            errPseudoNexistePas();
        }
    }else{
       errChmpsVides();
    }
}else{
    errFormulaire();
}