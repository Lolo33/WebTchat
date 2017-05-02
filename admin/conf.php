<?php
/**
 * Created by PhpStorm.
 * User: Lo�c
 * Date: 05/07/2016
 * Time: 14:11
 */

session_start();
$site = "Administration";

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

if ($_SESSION['rank'] < 3){
    header('Location: ../tchat.php');
    die();
}

function isBan($psd){
    $db = getConnexion();
    $req = $db->prepare('SELECT * FROM bans WHERE membre_banni = :pseudo AND ban_fin > NOW()');
    $req->bindValue(':pseudo', $psd, PDO::PARAM_STR);
    $req->execute();
    $isBan = $req->fetchColumn();
    if ($isBan == null){
        return false;
    }else {
        return true;
    }
}

$setting = $db->query("SELECT * FROM retrotalk_settings");
$setting->execute();
$settings = $setting->fetch(PDO::FETCH_ASSOC);

function dureeToSql($duree){
    switch ($duree){
        case '2 heures':
            $interval = "2 HOUR";
            break;
        case '6 heures':
            $interval = "6 HOUR";
            break;
        case '24 heures':
            $interval = "1 DAY";
            break;
        case '7 jours':
            $interval = "7 DAY";
            break;
        case "A vie":
        case "def":
            $interval = "50 YEAR";
            break;
        default:
            $interval = "";
            break;
    }
    return $interval;
}

function extension_is_correct($nom){
    $extensions = array('.png', '.gif', '.jpg', '.jpeg', '.bmp', '.BMP', '.JPG', '.JPEG', '.PNG', '.GIF', ".ico", ".ICO");
    // récupère la partie de la chaine à partir du dernier . pour connaître l'extension.
    $extension = strrchr($nom, '.');
    //Ensuite on teste
    if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
        return false;
    return true;
}

function errFormulaire(){
    echo '<script>alert("Vous devez avoir rempli le formulaire pour acceder a cette page!");history.back();</script>';
}

function errChmpsVides(){
    echo '<script>alert("Vous ne pouvez pas laisser de champs vide!");history.back();</script>';
}

function errPseudoNexistePas(){
    echo '<script>alert("Ce pseudo n\'existe pas!!");history.back();</script>';
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

