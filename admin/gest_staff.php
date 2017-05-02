<?php
/**
 * Created by PhpStorm.
 * User: Lo�c
 * Date: 09/07/2016
 * Time: 04:39
 */

require_once 'conf.php';
if ($_SESSION['rank'] < 4){
    header('Location: index.php');
}
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

$req = $db->query('SELECT * FROM membres ORDER BY membre_msg DESC ');
$req->execute();

$requete2 = $db->query('SELECT * FROM membres WHERE membre_rank >= 3 OR membre_rank = 0;');
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
                <li><a href="ban.php">Bans</a></li>
                <li class="dropdown active">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Gerer <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li class="active <?php if ($_SESSION['rank'] == 3){echo 'disabled';}?>"><a href="gest_staff.php">Equipe</a></li>
                        <li disabled="" class="disabled <?php if ($_SESSION['rank'] == 3){echo 'disabled';}?>"><a href="gest_news.php">les News</a></li>
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
    <div id="col1l1" class="panel panel-primary" >
        <div class="panel-heading">
            <h3 class="panel-title">Rank un membre</h3>
        </div>
        <div class="panel-body" style="width: 90%; margin-top: 30px;">
            <form method="post" action="mod/rank.php" class="form-horizontal">
                <fieldset>
                    <div class="form-group">
                        <label for="pseudo" class="col-lg-2 control-label">Pseudo</label>
                        <div class="col-lg-10">
                            <input type="text" <?php if(isset($pseudo)){echo 'value="'.$pseudo.'"';} ?> class="form-control" id="pseudo" name="pseudo" placeholder="Pseudo">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="rank" class="col-lg-2 control-label">Poste</label>
                        <div class="col-lg-10">
                            <select class="form-control" id="rank" name="rank">
                                <option value="0">Partenaire</option>
                                <option value="3">Mod&eacute;rateur</option>
                                <option value="4">Administrateur</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" style="text-align: right;">
                        <div class="col-lg-10 col-lg-offset-2">
                            <button type="reset" class="btn btn-default" onclick="$('#pseudo').attr('value', '');;">Annuler</button>
                            <button type="submit" name="subm" class="btn btn-primary">Rank</button>
                        </div>
                    </div>

                </fieldset>
            </form>
        </div>



        </div>
        </div>

        <div class="col-sm-4">
            <div class="panel panel-primary" style="height: 500px; overflow: auto;">
                <div class="panel-heading">
                    <h3 class="panel-title">L' &Eacute;quipe</h3>
                </div>
                <div class="panel-body">
                    <div class="list-group" style="width: 100% overflow: auto;">
                        <?php
                        while($equipe = $requete2->fetch()){
                            switch ($equipe['membre_rank']){
                                case 0:
                                    $color = "#007f5e";
                                    break;
                                case 3:
                                    $color = "#C09853";
                                    break;
                                case 4:
                                    $color = '#d0534d';
                                    break;
                                default:
                                    $color = '#007f5e';
                                    break;

                            }
                            echo '<a class="list-group-item"><div style="width: 70%; display: inline-block; color:'.$color.'">['.rankToString($equipe['membre_rank']).'] - '.$equipe['membre_pseudo'].'</div>';
                            echo '<form method="post" action="mod/unrank.php" style=" width: 30%; display: inline-block; text-align: right; vertical-align: top;">
                            <button name="psd" value="' . $equipe['membre_pseudo']. '" class="btn btn-danger btn-xs">Unrank</button>
                          </form></a>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="col2l2" class="panel panel-primary" style="vertical-align: top; height: 800px; overflow: auto;">
        <div class="panel-heading">
            <h3 class="panel-title">Les membres</h3>
        </div>
        <div class="panel-body">
            <div class="list-group" style="width: 100% overflow: auto;">
                <?php while ($membre = $req->fetch()){
                    echo '<a href="#" class="list-group-item"><div style="width: 50%; display: inline-block;">'.$membre['membre_pseudo'].'</div>';
                    if ($membre['membre_rank'] == 0 || $membre['membre_rank'] >= 3) {
                        echo '<form method="post" action="mod/unrank.php" style=" width: 50%; display: inline-block; text-align: right;">
                            <button id="btn-ban" name="psd" value="' . $membre['membre_pseudo'] . '" class="btn btn-danger btn-xs">Unrank</button>
                        </form></a>';
                    }else{
                        echo '<form method="get" action="gest_staff.php" style=" width: 50%; display: inline-block; text-align: right;">
                            <button id="btn-ban" name="psd" value="' . $membre['membre_pseudo'] . '" class="btn btn-primary btn-xs">Rank</button>
                        </form></a>';
                    }
                } ?>
            </div>
        </div>
    </div>


</div>
</body>
