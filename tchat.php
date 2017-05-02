<?php
/**
 * Created by PhpStorm.
 * User: Lo�c
 * Date: 01/07/2016
 * Time: 04:32
 */
require_once 'inc/conf.php';
if (!isLogged()){
    header('Location: index.php');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo $site; ?> - Le Chat</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/style2.css" />
    <link href='https://fonts.googleapis.com/css?family=Josefin+Sans|Josefin+Slab' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Poiret+One' rel='stylesheet' type='text/css'>
    <link rel="icon" type="image/png" href="favicon.png" />
    <script src="js/jquery-1.12.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</head>
<body>

<?php
include('inc/nav.php'); ?>

<script>var isRank = false;</script>
<?php if ($_SESSION['rank'] >= 3){
    echo '<script>isRank = true;</script>';
}
$req1 = $db->query('SELECT * FROM messages ORDER BY msg_date desc LIMIT 10;');
$req1->execute();
?>

<div class="titre">Le Tchat</div>
<div style="display: none;" class="ki">

</div>





        <div id="chat-cont" style="width: 90%; background: #464545;">

            <div class="alert alert-dismissible alert-danger" style="width: 95%; margin: 15px auto;">
                <button type="button" class="close" data-dismiss="alert" onclick="$(this).hide('slow');">&times;</button>
                <b>Rappel:</b> Ne communiques jamais tes informations personnelles (num&eacute;ro de carte bancaire, mot de passe...) sur le tchat!<br />
                Si tu trouves un bug, n'h&eacute;sites pas a le  <a target="_blank"  href="rapport.php">Rapporter</a>
            </div>

            <div id="message-cont">
                <?php
                // on r�cup�re les 10 derniers messages post�s
                $req = $db->query('SELECT * FROM messages;');
                $req->execute();
                $count = $req->rowCount();
                $last10 = $count - 10;

                $requete = $db->query('SELECT * FROM messages ORDER BY id ;');

                while($messages = $requete->fetch()){

                    $req2 = $db->prepare('SELECT * FROM membres WHERE membre_pseudo = :pseudo');
                    $req2->bindValue(':pseudo', $messages['pseudo'], PDO::PARAM_STR);
                    $req2->execute();
                    $membre = $req2->fetch();
                    $req2 = $db->prepare('SELECT * FROM avatars WHERE id = :id');
                    $req2->bindValue(':id', $membre['membre_avatar'], PDO::PARAM_INT);
                    $req2->execute();
                    $avatar = $req2->fetch();

                    $req_emote = $db->query("SELECT * FROM emoticones;");
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
                    $message = $messages['contenu'];

                    $req_emote->execute();
                    $message = $messages['contenu'];
                    while ($emote = $req_emote->fetch()) {
                        $message = str_replace($emote["icon_react"], '<img src="'.$settings["emote_url"]. DIRECTORY_SEPARATOR . $emote["icon_url"].'" height="30" />', $message);
                    }

                    echo '<div id="'.$messages['id'].'" class="message-box" style="font-size: 15px; background: ' . $bg . '">
            <a href="profil.php?search=' . $membre['membre_pseudo'] . '" target="_blank" ><img width="25px" src="' . $settings['avatar_url'] . DIRECTORY_SEPARATOR . $avatar['url'] . '"  />
            <span id="label-pseudo" class="label label-default" style="' . $label . '">' . $messages['pseudo'] . '</span></a>
            <div class="msg-content" style="' . $style . '">' . $message . '</div>
        </div>';

                //echo "<p id=\"" . $donnees['id'] . "\">" . $donnees['pseudo'] . " dit : " . $donnees['contenu'] . "</p>";
                }

                $requete->closeCursor(); ?>
            </div>

            <form id="form-message" method="post" action="">
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" placeholder="Entrez votre message ici.." autocomplete="off" id="message" maxlength="255" class="form-control">
                        <span class="input-group-btn">
                            <button class="btn btn-default" id="btn-envoyer" type="button">Envoyer</button>
                        </span>
                    </div>
                </div>

                <div class="checkbox">
                    <label>
                        <input type="checkbox" id="scroll-act" value="act" checked> Activer le scroll automatique vers le bas du tchat
                    </label>
                </div>

            </form>
        </div>




<?php include 'inc/footer.php'; ?>

<!-- version 1: <script src="js/script.js"></script> -->
<!-- version 2: --> <script src="js/main.js"></script>

<script src="js/slimscroll/jquery.slimscroll.min.js"></script>
<script>
    ScrollBas();
    $('#message-cont').slimScroll({
        color: 'whitesmoke',
        size: '10px',
        height: '490px',
        alwaysVisible: true,
        width: '95%',
        distance: '10px',
        railVisible: true,
        railColor: 'whitesmoke',
        railOpacity: 0.3,
        start: 'bottom'
    });
</script>

</body>
</html>