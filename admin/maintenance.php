<?php
include './conf.php';
if ($_SESSION['rank'] != 4){
    header('Location: index.php');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration - Maintenance</title>
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
                <li class><a href="index.php">Accueil<span class="sr-only"></span></a></li>
                <li><a href="ban.php">Bans</a></li>
                <li class="dropdown active">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Gerer <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li class="<?php if ($_SESSION['rank'] == 3){echo 'disabled';}?>"><a href="gest_staff.php">Equipe</a></li>
                        <li class="disabled<?php if ($_SESSION['rank'] == 3){echo 'disabled';}?>"><a href="gest_news.php">Les News</a></li>
                        <li class="active <?php if ($_SESSION['rank'] == 3){echo 'disabled';}?>"><a href="maintenance.php">Maintenances</a></li>
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
    <div id="grand">
        <div class="panel panel-primary" >
            <div class="panel-heading">
                <h3 class="panel-title">Activer la maintenance</h3>
            </div>
            <div class="panel-body" style="width: 90%; margin-top: 30px;">
                <?php $req = $db->query('SELECT * FROM retrotalk_settings;');
                $req->execute();
                $mtn = $req->fetch();

                if($mtn['maintenance'] == 'false') { ?>
                <form method="post" action="mod/mtn.php" class="form-horizontal">
                    <fieldset>
                        <div class="form-group">
                            <label for="textArea" class="col-lg-2 control-label">Raison de la maintenance</label>
                            <div class="col-lg-10">
                                <textarea class="form-control" rows="3" id="textArea" name="reason" style="resize:none;" placeholder=" Raison ,Dur&eacute;e de la maintenance..."></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div style="text-align: right;">
                                <button type="submit" name="submit" class="btn btn-primary">Activer</button>
                            </div>
                        </div>
                    </fieldset>
                </form> <?php }else{ ?>
                    <form method="post" action="mod/mtn.php" class="form-horizontal">
                    <fieldset>

                        <div style="">
                            Maintenance activ&eacute;e pour la raison suivante: <br />
                                <strong><?php echo $mtn['reason']; ?></strong>
                        </div>

                        <div class="form-group">
                            <div style="text-align: right;">
                                <button type="submit" name="submit" class="btn btn-primary">Desactiver</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
                <?php }
                ?>
            </div>
        </div>

    </div>
</div>
</div>
</div>
</body>
</html>

   