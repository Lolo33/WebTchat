<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18/04/2017
 * Time: 20:35
 */

require_once 'conf.php';

$req_av = $db->query('SELECT * FROM avatars;');
$req_av->execute();
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration - Gerer les avatars</title>
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
    <script>var id_select = 0;</script>
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
                        <li class="<?php if ($_SESSION['rank'] == 3){echo 'disabled';}?>"><a href="gest_staff.php">Equipe</a></li>
                        <li class="disabled <?php if ($_SESSION['rank'] == 3){echo 'disabled';}?>"><a href="gest_news.php">Les News</a></li>
                        <li class="<?php if ($_SESSION['rank'] == 3){echo 'disabled';}?>"><a href="maintenance.php">Maintenances</a></li>
                        <li class="divider"></li>
                        <li><a href="emotes.php">Emoticones</a></li>
                        <li class="active"><a href="avatars.php">Avatars</a></li>
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
        <div class="col-md-7">
            <div class="panel panel-primary" id="avatar">
                <div class="panel-heading">
                    Ajouter / Modifier un avatar
                </div>
                <div class="panel-body" style="padding: 20px;" >
                    <?php

                    if (isset($_GET["id"]) && !empty($_GET["id"])){
                        $id = htmlspecialchars(trim($_GET['id']));
                        $req_av_current = $db->prepare("SELECT * FROM avatars WHERE id = :id");
                        $req_av_current->bindValue(":id", $id, PDO::PARAM_INT);
                        $req_av_current->execute();
                        $avatar_current = $req_av_current->fetch();
                        if ($req_av_current->rowCount() != 1 || $id == 0){
                            header("Location: avatars.php");
                        }

                        echo '<a style="font-size: 25px;" href="avatars.php">< Retour</a>
                        <div style="width: 100%; text-align: center;  margin-bottom: 4%; cursor: default;">
                                <img id="'.$avatar_current['id'].'" class="av"   width="100" src="../' . $settings["avatar_url"] . DIRECTORY_SEPARATOR . $avatar_current['url'] . '" />
                         </div>'; ?>


                        <form action="mod/modavatars.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">

                            <input type="hidden" name="MAX_FILE_SIZE" value="1234515151151" />

                            <div class="form-group">
                                <label class="col-lg-2 control-label">Fichier </label>
                                <input type="file" name="avatar" id="avatar" />
                            </div>

                            <div class="form-group">
                                <label class="col-lg-2 control-label">Genre</label>
                                <div class="col-lg-10">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="genre" id="garcon" value="H" <?php if($avatar_current["sexe"] == "H") { echo 'checked'; } ?> >
                                            Garçon
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="genre" id="fille" value="F" <?php if($avatar_current["sexe"] == "F") { echo 'checked'; } ?> >
                                            Fille
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-2 control-label">Rang minimum</label>
                                <div class="col-lg-10">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="isvip" id="vip" value="1" <?php if($avatar_current["is_vip"] == 0) { echo 'checked'; } ?> >
                                            Tout le monde
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="isvip" id="tlm" value="0" <?php if($avatar_current["is_vip"] == 1) { echo 'checked'; } ?> >
                                            VIP
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div style="text-align: right;">
                                    <button type="submit" name="submit" class="btn btn-primary">Modifier cet avatar</button>
                                </div>
                            </div>

                        </form>

                        <?php

                    }else{
                    ?>
                    <form action="mod/avatars.php" method="post" enctype="multipart/form-data">

                        <input type="hidden" name="MAX_FILE_SIZE" value="1234515151151" />
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Fichier </label>
                            <input type="file" name="avatar" id="avatar" />
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Genre</label>
                            <div class="col-lg-10">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="genre" id="garcon" value="H" checked>
                                        Garçon
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="genre" id="fille" value="F">
                                        Fille
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Rang minimum</label>
                            <div class="col-lg-10">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="isvip" id="tlm" value="0" checked>
                                        Tout le monde
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="isvip" id="vip" value="1">
                                        VIP
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div style="text-align: right;">
                                <button type="submit" name="submit" class="btn btn-primary">Ajouter cet avatar</button>
                            </div>
                        </div>

                    </form>

                    <?php } ?>

                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="panel panel-primary" id="avatar_select">
                <div class="panel-heading">
                    Les avatars disponibles
                </div>
                <div class="panel-body" style="padding: 20px;" >
                    <div class="av-cont">
                    <?php
                    $i = 0;
                        while ($avatars = $req_av->fetch()){
                            echo '<div id="'.$avatars["sexe"].'-'.$avatars["id"].'" class="img-av '.$avatars["sexe"].'"><img id="'.$avatars['id'].'" class="av"   width="100" src="../' . $settings["avatar_url"] . DIRECTORY_SEPARATOR . $avatars['url'] . '" /></div>';
                            $i++;
                        }
                    ?>
                    </div>
                    <div class="row" style="margin-top: 2%; padding: 3% 2% 0; border-top: 1px solid black;">
                        <div class="col-lg-6">
                            <button disabled class="disabled btn btn-danger" id="del_av" style="width: 100%;" onclick="document.location.replace('mod/delavatars.php?id=' + id_select);">
                                Supprimer
                            </button>
                        </div>
                        <div class="col-lg-6">
                            <button disabled class="disabled btn btn-primary" id="mod_av" style="width: 100%;" onclick="document.location.replace('avatars.php?id=' + id_select);">
                                Modifier
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<script>
    $(".img-av").click(function () {
        $("#mod_av").removeClass("disabled").removeAttr("disabled");
        $("#del_av").removeClass("disabled").removeAttr("disabled");

        $(".img-av").removeClass("F-focus").removeClass("H-focus");
        if ($(this).attr('id').indexOf("H") == 0)
            $(this).addClass("H-focus");
        else
            $(this).addClass("F-focus");
        id_select = $(this).attr('id').substring(2, $(this).attr('id').length);
        console.log(id_select);
    });


</script>

</body>
</html>
