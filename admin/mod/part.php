<?php
/**
 * Created by PhpStorm.
 * User: Loïc
 * Date: 29/07/2016
 * Time: 07:59
 */
include '../conf.php';

function supprPart($id)
{
    $db = getConnexion();
    $requete = $db->prepare('SELECT * FROM partenaires WHERE id = :id');
    $requete->bindValue(':id', $id, PDO::PARAM_INT);
    $requete->execute();
    $cpart = $requete->fetch();
    if ($cpart != NULL) {
        $req = $db->prepare('DELETE FROM partenaires WHERE id = :id');;
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->execute();
        $req2 = $db->prepare('UPDATE membres SET has_submit_part = 0 WHERE membre_pseudo = :pseudo;');
        $req2->bindValue(':pseudo', $cpart['pseudo'], PDO::PARAM_STR);
        $req2->execute();
        header('Location: ../gest_part.php');
    }else{
        errChmpsVides();
    }
}

if (isset($_POST['ref'])){
    if (!empty($_POST['ref'])) {
        $id = htmlspecialchars(trim($_POST['ref']));
        supprPart($id);
    }
}elseif (isset($_POST['acc'])) {
    if (!empty($_POST['acc'])) {
        $id = htmlspecialchars(trim($_POST['acc']));
        $req = $db->prepare('UPDATE partenaires SET is_actif = 1 WHERE id = :id');
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->execute();
        header('Location: ../gest_part.php');
    } else {
        errChmpsVides();
    }
}elseif (isset($_POST['del'])){
    if (!empty($_POST['del'])) {
        $id = htmlspecialchars(trim($_POST['del']));
        supprPart($id);
        header('Location: ../gest_part.php');
    }else{
        errPseudoNexistePas();
    }
}else{
    errFormulaire();
}