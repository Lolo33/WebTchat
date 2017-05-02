<?php
require_once 'conf.php';
$db = getConnexion();

$req_nom = $db->query("SELECT nom FROM retrotalk_settings");
$req_nom->execute();
$nom_tchat = $req_nom->fetchColumn();

$req = $db->query('SELECT pseudo FROM messages ORDER BY msg_date DESC; ');
$req->execute();

$req2 = $db->query('SELECT COUNT(id) FROM messages');
$req2->execute();
$nbrMessage = $req2->fetchColumn();

$req3 = $db->query('SELECT msg_date FROM messages ORDER BY id DESC LIMIT 1;');
$req3->execute();
$dernierMessage = $req3->fetchColumn();

$req4 = $db->query('SELECT * FROM messages ORDER BY msg_date;');
$req4->execute();

$req5 = $db->query('SELECT COUNT(id) FROM messages WHERE msg_date > DATE_SUB(NOW(), INTERVAL 1 DAY)');
$req5->execute();
$msg_ajd = $req5->fetchColumn();

$req6 = $db->query('SELECT COUNT(membre_id) FROM membres;');
$req6->execute();
$membres_inscrits = $req6->fetchColumn();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration - ChatLogs</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/style2.css" />
    <link href='https://fonts.googleapis.com/css?family=Josefin+Sans|Josefin+Slab' rel='stylesheet' type='text/css'>
    <link rel="icon" type="image/png" href="favicon.png" />
    <style>
        /* (200 / 250%) */
        @media screen and (max-width: 860px){
            .panel-body, #btn-ban {
                font-size: 10px;
            }
        }
    </style>
    <script src="../js/jquery-1.12.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
</head>
<body>

<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand">Administration</a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
            <ul class="nav navbar-nav">
                <li class="active"><a href="index.php">Accueil <span class="sr-only"></span></a></li>
                <li><a href="ban.php">Bans</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Gerer <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li class="<?php if ($_SESSION['rank'] == 3){echo 'disabled';}?>"><a href="gest_staff.php">Equipe</a></li>
                        <li class="disabled <?php if ($_SESSION['rank'] == 3){echo 'disabled';}?>"><a href="gest_news.php">Les News</a></li>
                        <li class="<?php if ($_SESSION['rank'] == 3){echo 'disabled';}?>"><a href="maintenance.php">Maintenances</a></li>
                        <li class="divider"></li>
                        <li><a href="emotes.php">Emoticones</a></li>
                        <li><a href="avatars.php">Avatars</a></li>
                    </ul>
                </li>
            </ul>
            <form class="navbar-form navbar-left" role="search">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Saisir un pseudo">
                </div>
                <button type="submit" class="btn btn-default">Chercher</button>
            </form>
        </div>
    </div>
</nav>

<div id="cont">

    <div class="row">
        <div class="col-sm-8">
    <div class="panel panel-primary" id="col1l1" >
        <div class="panel-heading">
            <h3 class="panel-title">Utilisateurs R&eacute;cents</h3>
        </div>
        <div class="panel-body">
            <div class="list-group" style="width: 100%">

                <?php 
                $_SESSION["pseudos_tchat"] = [];
                while ($membre = $req->fetch()){
                    $pseudo_actuel = $membre["pseudo"];
                    if (!in_array($pseudo_actuel, $_SESSION["pseudos_tchat"])){
                        echo '<a href="#" class="list-group-item"><div style="width: 60%; display: inline-block;">'.$membre['pseudo'].'</div>';
                        if (!isBan($membre['pseudo'])) {
                            echo '<form method="get" action="ban.php" style=" width: 40%; display: inline-block; text-align: right;">
                            <button id="btn-ban" name="psd" value="' . $membre['pseudo'] . '" class="btn btn-danger btn-xs">Bannir</button>
                            </form></a>';
                        }else{
                            echo '<form method="post" action="mod/unban.php" style=" width: 40%; display: inline-block; text-align: right;">
                            <button id="btn-ban" name="psd" value="' . $membre['pseudo'] . '" class="btn btn-primary btn-xs">D&eacute;bannir</button>
                            </form></a>';
                        }
                        $_SESSION["pseudos_tchat"][] = $pseudo_actuel;
                    }
                } ?>

            </div>
        </div>
    </div>
        </div>

        <div class="col-sm-4">
    <div class="panel panel-primary" id="col2l2">
        <div class="panel-heading">
            <h3 class="panel-title">General & Statistiques</h3>
        </div>
        <div class="panel-body">
            <div class="list-group" style="width: 100%">
                <a class="list-group-item">Nom du tchat : <form method="post" style="display: inline-block;" action="mod/chnge_name.php"><input name="nom" type="text" value="<?php echo $nom_tchat; ?>" /><input type="submit" value="Valider"></form></a>
                <a class="list-group-item">Nombre de Membres inscrits : <strong><?php echo $membres_inscrits; ?></strong></a>
                <a class="list-group-item">Nombre de messages total : <strong><?php echo $nbrMessage; ?></strong></a>
                <a class="list-group-item">Dernier message : <strong><?php echo $dernierMessage; ?></strong></a>
                <a class="list-group-item">Nombre de Messages depuis 24h : <strong><?php echo $msg_ajd; ?></strong></a>
            </div>
        </div>
    </div>
    </div>
    </div>

    <div class="row" id="grand">
        <div class="col-sm-12">
    <div class="panel panel-primary" >
        <div class="panel-heading">
            <h3 class="panel-title">Chatlogs</h3>
        </div>
        <div class="panel-body">
            <div id="scroll">
            <?php while ($messages = $req4->fetch()){
                echo '<div class="msg">'.$messages['pseudo'].': <div class="msg-content">['.$messages['msg_date'].']</div><div class="msg-content">'.$messages['contenu'].'</div></div>';
            } ?>
            </div>
            <div style="text-align: center; margin: 15px auto;"><a class="btn btn-danger" href="mod/delmsg.php">Effacer tous les messages du chat</a></div>
        </div>
    </div>
        </div>
    </div>

</div>

<script>
    document.getElementById("scroll").scrollTop = document.getElementById('scroll').scrollHeight;
</script>

</body>
</html>