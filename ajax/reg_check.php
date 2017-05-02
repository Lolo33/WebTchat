<?php
/**
 * Created by PhpStorm.
 * User: Loïc
 * Date: 05/07/2016
 * Time: 09:24
 */

include('../inc/conf.php');
if(isset($_POST['pseudo'])) {
    $password = htmlspecialchars(trim($_POST['pass']));
    $pseudo = htmlspecialchars(trim($_POST['pseudo']));
    $mail = htmlspecialchars(trim($_POST['mail']));
    $repassword = htmlspecialchars(trim($_POST['repass']));
    if(empty($_POST['pseudo'])){
        echo '<div class="alert alert-dismissible alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Erreur !</strong> Merci d\'indiquer un pseudo !
              </div>';
        die();
    }
    if(empty($_POST['pass'])){
        echo '<div class="alert alert-dismissible alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Erreur !</strong> Merci d\'indiquer un mot de passe !
              </div>';
        die();
    }
    if(empty($_POST['repass'])){
        echo '<div class="alert alert-dismissible alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Erreur !</strong> Merci de confirmer votre mot de passe !
              </div>';
        die();
    }
    if(empty($_POST['mail'])){
        echo '<div class="alert alert-dismissible alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Erreur !</strong> Merci d\'indiquer un mail !
              </div>';
        die();
    }
    if($password!=$repassword) {
        echo '<div class="alert alert-dismissible alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Erreur !</strong> Les mots de passe ne correspondent pas !
              </div>';
        die();
    }
    if(strlen($_POST['pseudo']) < 4) {
        echo '<div class="alert alert-dismissible alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Erreur !</strong> Le pseudo est trop court !
              </div>';
        die();
    }
    if(strlen($_POST['pass']) < 6) {
        echo '<div class="alert alert-dismissible alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Erreur !</strong> Le mot de passe est trop court !
              </div>';
        die();
    }
    if(pseudoExiste($pseudo)) {
        echo '<div class="alert alert-dismissible alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Erreur !</strong> Ce pseudo existe d&eacute;ja
              </div>';
        die();
    }
    if(mailExiste($mail)) {
        echo '<div class="alert alert-dismissible alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Erreur !</strong> Ce mail est d&eacute;ja enregistr&eacute;
              </div>';
        die();
    }
    if(!empty($_POST['pseudo']) && !empty($_POST['pass']) && !empty($_POST['repass']) && !empty($_POST['mail'])) {
        $req = $db->prepare("INSERT INTO membres (membre_pseudo, membre_mail, membre_password, membre_rank, ip_inscription) VALUES(:pseudo, :mail,:password, 1, :ip)");
        $req->execute(array(':pseudo' => $pseudo, ':mail' => $mail, ':password' => md5($password), ':ip' => $_SERVER['REMOTE_ADDR']));
        echo '<div class="alert alert-dismissible alert-success">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Bravo!</strong> Vous etes inscrit ! Vous allez &ecirc;tre redirig&eacute; vers la page de connexion pour vous y connecter !
                    </div>';
        $_SESSION['pseudo'] = $pseudo;
        $_SESSION['id'] = null;
        $_SESSION['rank'] = 1;
        $_SESSION['has-submit'] = 0;
        echo '<META http-equiv="refresh" content="3;URL=tchat.php">';
    }
}
