<?php
/**
 * Created by PhpStorm.
 * User: Lo�c
 * Date: 17/07/2016
 * Time: 21:40
 */

$host_name  = "mysql.hostinger.fr";
$database   = "u295338581_tchat";
$user_name  = "u295338581_tchat";
$password   = "lololepro24";

try {
    $db = new PDO('mysql:host=' . $host_name . ';dbname=' . $database . ';charset=utf8', '' . $user_name . '', $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

$requete = $db->prepare('SELECT * FROM bans WHERE ip = :ip AND ban_fin > NOW()');
$requete->bindValue(':ip', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
$requete->execute();
$isBan = $requete->fetchColumn();

if ($requete->rowCount() > 0){
    echo '<script>document.location.replace("index.php");</script>';
    die;
}else {

}

$setting = $db->query("SELECT * FROM retrotalk_settings");
$setting->execute();
$settings = $setting->fetch(PDO::FETCH_ASSOC);
if ($settings['maintenance'] == 'true'){
    die;
}

if(!empty($_GET['id'])){

    $id = (int) $_GET['id'];

    // on r�cup�re les messages ayant un id plus grand que celui donn�
    $requete = $db->prepare('SELECT * FROM messages WHERE id > :id ORDER BY id ');
    $requete->execute(array("id" => $id));

    $messages = null;

    if($requete->rowCount() == 0){
        die;
    }else {

        while ($messages = $requete->fetch()) {

            if ($messages['id'] != $id) {

                // req membres
                $req2 = $db->prepare('SELECT * FROM membres WHERE membre_pseudo = :pseudo');
                $req2->bindValue(':pseudo', $messages['pseudo'], PDO::PARAM_STR);
                $req2->execute();
                $membre = $req2->fetch();
                // req avatar
                $req2 = $db->prepare('SELECT * FROM avatars WHERE id = :id');
                $req2->bindValue(':id', $membre['membre_avatar'], PDO::PARAM_INT);
                $req2->execute();
                $avatar = $req2->fetch();

                $req_emote = $db->query("SELECT * FROM emoticones;");
                // d�finition des styles
                if ($membre['membre_rank'] >= 3) {
                    $bg = 'rgba(244,166,42,0.2); font-family: \'Poiret One\', cursive;';
                    $label = 'background: rgba(244,166,42,1.0);';
                    $style = 'font-weight: bold;';
                } else {
                    $bg = 'rgba(140,152,153,0.2); font-family: segoe print;';
                    $label = "";
                    $style = "";
                    if ($membre['membre_rank'] == 1)
                        $req_emote = $db->query("SELECT * FROM emoticones WHERE icon_is_vip = 0;");
                }

                $req_emote->execute();
                $message = $messages['contenu'];

                while ($emote = $req_emote->fetch()) {
                    $message = str_replace($emote["icon_react"], '<img src="' . $settings["emote_url"] . DIRECTORY_SEPARATOR . $emote["icon_url"] . '" height="30" />', $message);
                }

                echo '<script>if ($("#scroll-act").prop("checked")){ScrollBas();}</script>
                <div id="' . $messages['id'] . '" class="message-box" style="font-size: 15px; background: ' . $bg . '">
                <a href="profil.php?search=' . $membre['membre_pseudo'] . '" target="_blank" ><img width="25px" src="' . $settings['avatar_url'] . DIRECTORY_SEPARATOR . $avatar['url'] . '"  />
                <span id="label-pseudo" class="label label-default" style="' . $label . '">' . $messages['pseudo'] . '</span></a>
                <div class="msg-content" style="' . $style . '">' . $message . '</div>
                </div>
                <audio src="sons/Facebook.mp3" autoplay>';
            }
        }
    }

}
