<?php
/**
 * Created by PhpStorm.
 * User: Loïc
 * Date: 30/06/2016
 * Time: 11:44
 */
require_once '../inc/conf.php';

if (isset($_POST['pseudo'])){
    $pseudo = htmlspecialchars(trim($_POST['pseudo']));
    $mdp = htmlspecialchars(trim($_POST['pass']));
    if (!empty($pseudo) && !empty($mdp)){
        $req1 = $db->prepare('SELECT * FROM membres WHERE membre_pseudo = :pseudo OR membre_mail = :pseudo');
        $req1->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
        $req1->execute();
        $umembre = $req1->fetch();
        if ($umembre != null){
            $req2 = $db->prepare('SELECT membre_password FROM membres WHERE membre_pseudo = :pseudo OR membre_mail = :pseudo');
            $req2->bindValue(':pseudo', $umembre['membre_pseudo'], PDO::PARAM_STR);
            $req2->execute();
            $pass = $req2->fetchColumn();
            if (md5($mdp) == $pass){
                echo '<div class="alert alert-dismissible alert-success">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Bravo!</strong> Vous etes connect&eacute;! Vous allez etre redirig&eacute; vers l\'espace membre!
                    </div>';
                $req3 = $db->prepare('UPDATE membres SET ip_last_conn = :ip WHERE membre_id = :id');
                $req3->bindParam(':ip', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
                $req3->bindParam(':id', $umembre['membre_id'], PDO::PARAM_INT);
                $req3->execute();
                $_SESSION['pseudo'] = $umembre['membre_pseudo'];
                $_SESSION['id'] = $umembre['membre_id'];
                $_SESSION['rank'] = $umembre['membre_rank'];
                $_SESSION['has-submit'] = $umembre['has_submit_part'];
                if (isBan()){
                    echo '<script>document.location.replace("banni.php");</script>';
                }else {
                    echo '<meta http-equiv="refresh" content="3;URL=tchat.php" />';
                }
            }else{
                echo '<div class="alert alert-dismissible alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Erreur!</strong> Le mot de passe est erron&eacute;
              </div>';
            }
        }else{
            echo '<div class="alert alert-dismissible alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Erreur!</strong> Ce pseudo n\'existe pas !
              </div>';
        }
    }else{
        echo '<div class="alert alert-dismissible alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Erreur!</strong> Veuillez remplir tous les champs!
              </div>';
    }
}else{
    echo '<div class="alert alert-dismissible alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Erreur!</strong> Veuillez remplir le formulaire!
              </div>';
}