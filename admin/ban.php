<?php
/**
 * Created by PhpStorm.
 * User: Lo�c
 * Date: 06/07/2016
 * Time: 00:45
 */
require_once 'conf.php';
if (isset($_GET['psd'])){
    $pseudo = htmlspecialchars(trim($_GET['psd']));
    $req = $db->prepare('SELECT membre_pseudo FROM membres WHERE membre_pseudo = :pseudo');
    $req->bindValue(':pseudo',$pseudo, PDO::PARAM_STR);
    $req->execute();
    $psd = $req->fetchColumn();
    if ($psd == NULL){
        echo '<script>alert("Ce pseudo n\'existe pas!");document.location.replace("index.php")</script>';
    }
}

$requete2 = $db->query('SELECT * FROM bans;');
$requete2->execute();

$req2 = $db->query("SELECT * FROM bans;");
$req2->execute();
$nbr_bans = $req2->rowCount();

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
                <li><a href="index.php">Accueil <span class="sr-only"></span></a></li>
                <li class="active"><a href="ban.php">Bans</a></li>
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

    <div id="grand" class="panel panel-primary" >
        <div class="panel-heading">
            <h3 class="panel-title">Bannir un membre</h3>
        </div>
        <div class="panel-body" style="width: 90%; margin-top: 30px;">
            <form method="post" action="mod/banuser.php" class="form-horizontal">
                <fieldset>
                    <div class="form-group">
                        <label for="inputEmail" class="col-lg-2 control-label">Pseudo</label>
                        <div class="col-lg-10">
                            <input type="text" <?php if(isset($pseudo)){echo 'value="'.$pseudo.'"';} ?>class="form-control" id="pseudo" name="pseudo" placeholder="Pseudo">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="select" class="col-lg-2 control-label">Dur&eacute;e</label>
                        <div class="col-lg-10">
                            <select class="form-control" id="duree" name="duree">
                                <option>2 heures</option>
                                <option>6 heures</option>
                                <option>24 heures</option>
                                <option>7 jours</option>
                                <option>A vie</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="textArea" class="col-lg-2 control-label">Raison</label>
                        <div class="col-lg-10">
                            <textarea placeholder="Raison du ban..." class="form-control" rows="3" id="raison" name="raison"></textarea>
                            <span class="help-block"></span>
                        </div>
                    </div>

                    <div class="form-group" style="text-align: right;">
                        <div class="col-lg-10 col-lg-offset-2">
                            <button type="reset" class="btn btn-default" onclick="$('#pseudo').attr('value', '');;">Annuler</button>
                            <button type="submit" name="subm" class="btn btn-primary">Bannir</button>
                        </div>
                    </div>

                </fieldset>
            </form>
        </div>
    </div>



    <div class="row">
        <div class="col-sm-4">
   <div id="col1l1" class="panel panel-primary" >
        <div class="panel-heading">
            <h3 class="panel-title">Membres bannis</h3>
        </div>
        <div class="panel-body">
            <div class="list-group" style="width: 100% overflow: auto;">
                <?php
                while($bans = $requete2->fetch()){
                    echo '<a class="list-group-item"><div style="width: 70%; display: inline-block;">'.$bans['membre_banni'].'<br  />'.$bans['duree_str'].' <br /> '.$bans['raison'].'</div>';
                    echo '<form method="post" action="mod/unban.php" style=" width: 30%; display: inline-block; text-align: right; vertical-align: top;">
                            <button id="btn-ban" name="psd" value="' . $bans['membre_banni']. '" class="btn btn-primary btn-xs">D&eacute;bannir</button>
                          </form></a>';
                } ?>
            </div>
        </div>
   </div>
    </div>


        <div class="col-sm-8">
    <div id="col2l2" class="panel panel-primary" >
        <div class="panel-heading">
            <h3 class="panel-title">Statistiques</h3>
        </div>
        <div class="panel-body">
            <div class="list-group" style="width: 100%">
                <a href="#" class="list-group-item">Nombre de bans total : <strong><?php echo $nbr_bans; ?></strong></a>
            </div>
        </div>
    </div>
        </div>
    </div>
</div>
