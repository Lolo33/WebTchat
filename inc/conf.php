<?php
/**
 * Created by PhpStorm.
 * User: Lo�c
 * Date: 30/06/2016
 * Time: 11:54
 */

session_start();
global $countMsg;
global $site;
$countMsg = 0;

function getConnexion()
{
    $host_name  = "localhost";
    $database   = "tchat";
    $user_name  = "root";
    $password   = "";

    $host_name  = "mysql.hostinger.fr";
    $database   = "u295338581_tchat";
    $user_name  = "u295338581_tchat";
    $password   = "lololepro24";

    try {
        $db = new PDO('mysql:host=' . $host_name . ';dbname=' . $database . ';charset=utf8', '' . $user_name . '', $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        return $db;
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
}

$db = getConnexion();

function isLogged() {
    if (isset($_SESSION['pseudo'])){
        return true;
    }else{
        return false;
    }
}

function pseudoExiste($pseudo){
    $db = getConnexion();
    $req = $db->prepare('SELECT * FROM membres WHERE membre_pseudo = :pseudo');
    $req->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
    $req->execute();
    $pseud = $req->fetch();
    if ($pseud['membre_pseudo'] != null)
        return true;
    else
        return false;
}

function mailExiste($mail){
    $db = getConnexion();
    $req = $db->prepare('SELECT * FROM membres WHERE membre_mail = :mail');
    $req->bindValue(':mail', $mail, PDO::PARAM_STR);
    $req->execute();
    $email = $req->fetch();
    if ($email['membre_mail'] != null)
        return true;
    else
        return false;
}

function isBan(){
    $db = getConnexion();
    $req = $db->prepare('SELECT * FROM bans WHERE ip = :ip AND ban_fin > NOW()');
    $req->bindValue(':ip', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
    $req->execute();
    $isBan = $req->fetchColumn();
    if ($isBan == null){
        return false;
    }else {
        return true;
    }
}

function errChat($message){
    echo '<script>if ($("#scroll-act").prop("checked")){ScrollBas();}</script>
        <div class="message-box" style="background: rgba(234,97,83,0.5); ">
        <span class="label label-danger" style="margin-right: 5px; height: inherit;">Erreur: </span>
        <div class="msg-content">'.$message.'</div></div>';
}

function succesChat($message){
    echo '<script>if ($("#scroll-act").prop("checked")){ScrollBas();}</script>
        <div class="message-box" style="background: #27ae60; ">
        <span class="label label-success" style="margin-right: 5px; height: inherit;">Réussite </span>
        <div class="msg-content">'.$message.'</div></div>';
}

$setting = $db->query("SELECT * FROM retrotalk_settings");
$setting->execute();
$settings = $setting->fetch(PDO::FETCH_ASSOC);

if($settings['maintenance'] == 'true') {
    header('Location: maintenance.php');
}

function rankToString($rank_user)
{
    switch ($rank_user) {
        case 0:
            $rank = 'Partenaire';
            break;
        case 1:
            $rank = 'Utilisateur';
            break;
        case 2:
            $rank = 'VIP';
            break;
        case 3:
            $rank = 'Mod&eacute;rateur';
            break;
        case 4:
            $rank = "Administrateur";
            break;
        default:
            $rank = "";
    }
    return $rank;
}

function errPseudoNexistePas(){
    echo '<script>alert("Ce pseudo n\'existe pas!!");history.back();</script>';
}

if (isBan()){;
    header('Location: banni.php');
}

$site = $settings["nom"];

