<?php
/**
 * Created by PhpStorm.
 * User: Loïc
 * Date: 12/07/2016
 * Time: 05:52
 */
require_once '../inc/conf.php';
if (isset($_POST['newmail']) && isset($_POST['passw'])){
    $new_mail = htmlspecialchars(trim($_POST['newmail']));
    $passw_saisi = htmlspecialchars(trim($_POST['passw']));
    $pseudo = $_POST['psd'];

    if (!empty($new_mail) && !empty($passw_saisi)) {
        $req = $db->prepare('SELECT * FROM membres WHERE membre_pseudo = :pseudo');
        $req->bindValue(':pseudo', $passw_saisi, PDO::PARAM_STR);
        $req->execute();
        $membre = $req->fetch();

        $requete_mail_libre = $db->prepare('SELECT * FROM membres WHERE membre_mail = :mail');
        $requete_mail_libre->bindValue(':mail', $new_mail, PDO::PARAM_STR);
        $requete_mail_libre->execute();
        $mail_existe = $requete_mail_libre->fetch();

        if (empty($mail_existe)) {
            if ($passw_saisi == $membre['membre_password'] || $_SESSION['rank'] >= 3) {
                $req2 = $db->prepare('UPDATE membres SET membre_mail = :mail WHERE membre_pseudo = :pseudo');
                $req2->bindParam(':mail', $new_mail, PDO::PARAM_STR);
                $req2->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
                $req2->execute();
                echo '<div class="alert alert-dismissible alert-success">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Bravo!</strong> Vous avez bien chang votre mail!
              </div>';
                $_SESSION['mail'] = $new_mail;
                echo '<script>document.location.reload();</script>';
            } else {
                echo '<div class="alert alert-dismissible alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Erreur: </strong> Le mot de passe n\'est pas le bon
              </div>';
            }
        } else {
            echo '<div class="alert alert-dismissible alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Erreur: </strong> Ce mail exite d&eacute;ja !
              </div>';
        }
    }else{
        echo '<div class="alert alert-dismissible alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Erreur: </strong> Vous devez remplir tous les champs
              </div>';
    }
}else{
    header('Location: ../tchat.php');
}