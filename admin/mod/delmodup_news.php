<?php
/**
 * Created by PhpStorm.
 * User: Loïc
 * Date: 27/07/2016
 * Time: 03:38
 */

include '../conf.php';
$db = getConnexion();
if ($_SESSION['rank'] <= 3){
    header('Location: ../index.php');
}

if (isset($_GET['id']) && !empty($_GET['id']) && isset($_POST['del'])){
    $id = $_GET['id'];
    $req = $db->prepare('DELETE FROM news WHERE id = :id');
    $req->bindValue(':id', $id, PDO::PARAM_INT);
    $req->execute();
    header('Location: ../gest_news.php');
}elseif (isset($_POST['add'])){
    if (!empty($_POST['titre']) && !empty($_POST['accr']) && !empty($_POST['contenu'])){
        $titre = htmlspecialchars(trim($_POST['titre']));
        $accr = htmlspecialchars(trim($_POST['accr']));
        $contenu = nl2br(trim($_POST['contenu']));
        $req = $db->prepare('INSERT INTO news (titre, contenu, nws_date, accroche, staff) VALUES (?, ?, NOW(), ?, ?)');
        $req->execute(array($titre, $contenu, $accr, $_SESSION['pseudo']));
        header('Location: ../gest_news.php');
    }else{
        errChmpsVides();
    }
}elseif (isset($_GET['id']) && !empty($_GET['id']) && isset($_POST['mod'])){
    if (!empty($_POST['titre']) && !empty($_POST['accr']) && !empty($_POST['contenu'])){
        $titre = htmlspecialchars(trim($_POST['titre']));
        $accr = htmlspecialchars(trim($_POST['accr']));
        $contenu = nl2br(trim($_POST['contenu']));
        $req = $db->prepare('UPDATE news SET titre = ?, contenu = ?, nws_date = NOW(), accroche = ? WHERE id = ?');
        $req->execute(array($titre, $contenu, $accr, $_GET['id']));
        header('Location: ../gest_news.php');
    }else{
        errChmpsVides();
    }
}else{
    errFormulaire();
}