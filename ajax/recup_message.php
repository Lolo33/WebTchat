<?php
/**
 * Created by PhpStorm.
 * User: Loïc
 * Date: 01/07/2016
 * Time: 05:50
 */

$host_name  = "localhost";
$database   = "tchat";
$user_name  = "root";
$password   = "";
try {
    $db = new PDO('mysql:host=' . $host_name . ';dbname=' . $database . ';charset=utf8', '' . $user_name . '', $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

$requete = $db->prepare('SELECT * FROM bans WHERE ip = :ip AND ban_fin > NOW()');
$requete->bindValue(':ip', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
$requete->execute();
$isBan = $requete->fetchColumn();
if ($isBan == null){
}else {
    echo '<script>document.location.replace("banni.php");</script>';
}

$setting = $db->query("SELECT * FROM retrotalk_settings");
$setting->execute();
$settings = $setting->fetch(PDO::FETCH_ASSOC);
if ($settings['maintenance'] == 'true'){
    echo '<script>document.location.replace("maintenance.php");</script>';
}

$req1 = $db->prepare('SELECT * FROM messages ORDER BY msg_date;');
$req1->execute();

while ($messages = $req1->fetch()) {
    $req2 = $db->prepare('SELECT * FROM membres WHERE membre_pseudo = :pseudo');
    $req2->bindValue(':pseudo', $messages['pseudo'], PDO::PARAM_STR);
    $req2->execute();
    $membre = $req2->fetch();
    $req2 = $db->prepare('SELECT * FROM avatars WHERE id = :id');
    $req2->bindValue(':id', $membre['membre_avatar'], PDO::PARAM_INT);
    $req2->execute();
    $avatar = $req2->fetch();

    if ($membre['membre_rank'] >= 3) {
        $bg = 'rgba(244,166,42,0.2); font-family: \'Poiret One\', cursive;';
        $label = 'background: rgba(244,166,42,1.0);';
        $style = 'font-weight: bold;';
    } else {
        $bg = 'rgba(140,152,153,0.2); font-family: segoe print;';
        $label = "";
        $style = "";
    }
    $message = $messages['contenu'];

        echo '<div id="'.$messages['id'].'" class="message-box" style="font-size: 15px; background: ' . $bg . '">
            <a href="profil.php?search=' . $membre['membre_pseudo'] . '" target="_blank" ><img width="30px;" src="img/avatars/' . $avatar['url'] . '" />
            <span id="label-pseudo" class="label label-default" style="' . $label . '">' . $messages['pseudo'] . '</span></a>
            <div class="msg-content" style="' . $style . '">' . $message . '</div>
        </div>';

}
