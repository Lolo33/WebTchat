<?php

include('inc/conf.php');

if (!isLogged()){
    header('Location: index.php');
}

/*
 * Traitement du GET
 */
// remplit $pseudo par le pseudo de l'url si il yen a un, par le pseudo de la session si non / Affiche ou non bouton modifier
if (isset($_GET['search']) && !empty($_GET['search'])){
    $pseudo = htmlspecialchars(trim($_GET['search']));
}else{
    $pseudo = $_SESSION['pseudo'];
}

$btnmodif = '<span class="label label-info" style="cursor:pointer;">Modifier</span>';

/*
 * Membre
 */
// r�cup�re le membre pour tester si il existe
$req = $db->prepare('SELECT * FROM membres WHERE membre_pseudo = :pseudo');
$req->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
$req->execute();
$membre = $req->fetch();
$pseudo = $membre['membre_pseudo'];

// ERREYR : PROFIL NEXISTE PAS
if (empty($membre['membre_pseudo'])){
    ?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $site; ?> - Profil non trouvé</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/style2.css" />
    <link href='https://fonts.googleapis.com/css?family=Josefin+Sans|Josefin+Slab' rel='stylesheet' type='text/css'>
    <link rel="icon" type="image/png" href="favicon.png" />
    <script src="js/jquery-1.12.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</head>
<body>

<?php include('inc/nav.php'); ?>
    <div class="titre">Profil non-existant</div>
    <form class="form-horizontal" style="width: 75%; margin: auto; margin-top: 30px; ">
        <fieldset>
            <div class="form-group">
                <div class="input-group">
                    <input type="text" placeholder="Tapez un pseudo pour afficher son profil" autocomplete="off" name="search" maxlength="120" class="form-control">
                    <span class="input-group-btn">
                    <button style="height: 45px;" class="btn btn-default" type="submit">Rechercher</button>
                </span>
                </div>
            </div>
        </fieldset>
    </form>
    <div class="titre" style="margin-top: -60px;"></div>

    <div id="conteneur-index">
        <div style=" text-align: center; color: white; font-weight: bold; font-size: 25px; height: 315px; line-height: 300px;">
            ERREUR : Aucun profil ne correspond a votre recherche
        </div>
    </div>

    <?php
}else{

// Remplit une description si elle est vide
if ($membre['membre_description'] == NULL)
    $description = "Je suis nouveau sur ".$site;
else
    $description = $membre['membre_description'];


/*
 * Avatar
 */
// r�cup�re les informations de l'avatar du membre
$req2 = $db->prepare('SELECT * FROM avatars WHERE id = :id');
$req2->bindValue(':id', $membre['membre_avatar'], PDO::PARAM_INT);
$req2->execute();
$avatar = $req2->fetch();

// r�cup�re les avatars disponibles en fonction du rang
$rank = $_SESSION['rank'];
if ($rank == 1){
    $reqF = $db->query('SELECT * FROM avatars WHERE is_vip = 0 AND sexe = \'F\';');
    $reqH = $db->query('SELECT * FROM avatars WHERE is_vip = 0 AND sexe = \'H\';');
}else{
    $reqF = $db->query('SELECT * FROM avatars WHERE sexe = \'F\';');
    $reqH = $db->query('SELECT * FROM avatars WHERE sexe = \'H\';');
}
$reqF->execute();
$reqH->execute();

?>

<!DOCTYPE html>
   <head>
        <meta charset="UTF-8">
        <title><?php echo $site.'- '.$pseudo; ?></title>
        <link rel="stylesheet" href="css/style.css" />
        <link rel="stylesheet" href="css/style2.css" />
        <link href='https://fonts.googleapis.com/css?family=Josefin+Sans|Josefin+Slab' rel='stylesheet' type='text/css'>
        <link rel="icon" type="image/png" href="favicon.png" />
        <script src="js/jquery-1.12.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </head>
<body>

<?php include('inc/nav.php'); ?>

<div class="titre">Profil: <?php echo $pseudo; ?></div>

<form class="form-horizontal" style="width: 75%; margin: auto; margin-top: 30px; ">
    <fieldset>

        <div class="form-group">
            <div class="input-group">
                <input type="text" placeholder="Tapez un pseudo pour afficher son profil" autocomplete="off" name="search" maxlength="120" class="form-control">
                <span class="input-group-btn">
                    <button style="height: 45px;" class="btn btn-default" type="submit">Rechercher</button>
                </span>
            </div>
        </div>

    </fieldset>
</form>

<div class="titre" style="margin-top: -60px;"></div>

    <div id="conteneur-index">

        <div class="panel panel-primary" id="modif_avatar" style="display:none;">
            <div class="panel-heading">
                <h3 class="panel-title">Modifier mon avatar</h3><h3 id="fermer-av">X</h3>
            </div>
            <div class="panel-body" style="padding: 20px;" >
                <form class="form-horizontal" id="form-avatar" method="post">
                    <fieldset>
                        <ul class="nav nav-pills">
                            <li id="link-pills" class="active"><a>Mec</a></li>
                            <li id="link-pillsf"><a>Fille</a></li>
                        </ul>

                        <div id="av-garcon">
                            <div class="av-cont">
                                <?php
                                while ($avatars = $reqH->fetch()) {
                                    echo '<div class="img-av"><img id="'.$avatars['id'].'" class="av"   width="100" src="'. $settings["avatar_url"] . DIRECTORY_SEPARATOR . $avatars['url'] . '" /></div>';
                                }
                                ?>
                            </div>
                        </div>

                        <div id="av-fille">
                            <div class="av-cont">
                                <?php
                                if ($reqF->rowCount() > 0) {
                                    while ($avatars = $reqF->fetch()) {
                                        echo '<div class="img-av"><img id="' . $avatars['id'] . '"  width="100" class="av" src="'. $settings["avatar_url"] . DIRECTORY_SEPARATOR . $avatars['url'] . '" /></div>';
                                    }
                                }
                                ?>
                            </div>
                        </div>

                        <div style="text-align: right;"><button class="btn btn-success" name="submit">Selectionner cet avatar</button></div>

                    </fieldset>
                </form>
            </div>
        </div>

        <div class="panel panel-primary" id="modifier_desc" style="display:none;">
            <div class="panel-heading">
                <h3 class="panel-title">Modifier ma description</h3><h3 id="fermer-desc">X</h3>
            </div>
            <div class="panel-body" id="conteneur-index">
                <form autocomplete="off" id="form-desc" class="form-horizontal" style="width: 90%;" method="post" >
                    <fieldset>

                        <div class="form-group">
                            <label for="textArea" class="control-label">Nouvelle D&eacute;scription</label>
                            <textarea class="form-control" rows="3" placeholder="Humeur, profil, motivations ... D&eacute;crit toi !;" id="desc"></textarea>
                            <span class="help-block"></span>
                        </div>

                        <div style="text-align: right;"><button class="btn btn-success" name="submit">Changer ma description</button></div>

                    </fieldset>
                </form>
            </div>
        </div>

        <div id="erreur-mail"></div>

        <div class="panel panel-primary" id="modif_mail" style="display:none;">
            <div class="panel-heading">
                <h3 class="panel-title">Modifier mon e-mail</h3><h3 id="fermer-mail">X</h3>
            </div>
            <div class="panel-body" id="conteneur-index">
                <form autocomplete="off" id="form-mail" class="form-horizontal" style="width: 90%;" method="post" >
                    <fieldset>

                        <div class="form-group">
                            <label for="newmail" class="col-lg-2 control-label">Nouvealle adresse mail</label>
                            <div class="col-lg-10">
                                <input type="email" class="form-control" id="newmail">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="passw" class="col-lg-2 control-label">Mot de passe actuel</label>
                            <div class="col-lg-10">
                                <input type="password" class="form-control" id="passw">
                            </div>
                        </div>

                        <div style="text-align: right;"><button class="btn btn-success" name="submit">Changer mon adresse mail</button></div>

                    </fieldset>
                </form>
            </div>
        </div>




        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 id="psd" class="panel-title"><?php echo $pseudo ?></h3>
            </div>
            <div class="panel-body" style="font-size: 20px; padding: 20px;">
                <div id="info-user">
                    <p style="padding: 3px;">Informations de l'utilisateur:</p>
                    <ul class="list-group">
                        <li class="list-group-item bggris">
                            <?php echo $pseudo; ?>
                        </li>
                        <li class="list-group-item bggris">
                            <?php echo $membre['membre_mail'];
                            if ($pseudo == $_SESSION['pseudo'] || $_SESSION['rank'] >= 3)
                                echo '<div class="col2-info" id="modifier_mail" >'.$btnmodif.'</div>';
                            ?>
                        </li>
                        <li class="list-group-item bggris">
                            <?php echo rankToString($membre['membre_rank']) ?>
                        </li>
                    </ul>
                    <p class="description" >
                        <?php
                        echo $description;
                        if ($pseudo == $_SESSION['pseudo'] || $_SESSION['rank'] >= 3){
                            echo '<div id="modif_desc">
                                    <span class="label label-info" style="cursor:pointer;">Modifier</span>
                                </div>';
                        } ?>
                    </p>
                </div>

                <div id="avatars">
                    <p>Avatar:</p>
                    <img src="<?php echo ''. $settings["avatar_url"] . DIRECTORY_SEPARATOR . $avatar['url']; ?>" width="100" />
                    <?php if ($pseudo == $_SESSION['pseudo'] || $_SESSION['rank'] >= 3)
                        echo '<div id="modifier_avatar">'.$btnmodif.'</div>'; ?>
                    <p style="margin-top: 34px;">Messages : <?php echo $membre['membre_msg']; ?></p>
                </div>
            </div>
        </div>

</div>

<?php } ?>

<script>

    $("#link-pillsf").click(function() {
        $("#av-garcon").hide();
        $("#av-fille").show();
        $("#link-pills").removeClass("active");
        $(this).addClass("activef");
    });

    $("#link-pills").click(function() {
        $("#av-fille").hide();
        $("#av-garcon").show();
        $("#link-pillsf").removeClass("activef");
        $(this).addClass("active");
    });

    $('#fermer-mail').click(function() {
        $('#modif_mail').slideUp();
    });

    $('#fermer-av').click(function() {
        $('#modif_avatar').slideUp();
    });

    $('#fermer-desc').click(function() {
        $('#modifier_desc').slideUp();
    });

    $('.av').click(function() {
        $('.av').removeClass('av-act');
        $(this).addClass('av-act');
    });

    $('#form-mail').submit(function(){
        var newmail = $('#newmail').val();
        var passw = $('#passw').val();
        var psd = $('#psd').text();

        $.post('ajax/chng_mail.php', { newmail:newmail, passw:passw, psd:psd }, function(data) {
            $('#erreur-mail').html(data);
        });

        return false;
    });

    $('#form-desc').submit(function(){
        var desc = $('#desc').val();
        var psd = $('#psd').text();

        $.post('ajax/chng_desc.php', {desc:desc, psd:psd}, function(){
            if (desc != null)
                document.location.reload();
        });

        return false;
    });

    $('#form-avatar').submit(function(){
        var imgid = $('.av-act').attr('id');
        var psd = $('#psd').text();

        $.post('ajax/chng_avatar.php', { imgid:imgid, psd:psd }, function(){

            if (imgid != null)
                document.location.reload();
        });

        return false;
    });

    $('#modif_desc').click(function() {
        var modifmail = $("#modifier_desc");
        if (modifmail.css("display") == 'none')
            modifmail.slideDown();
        else
            modifmail.slideUp();
    });

    $('#modifier_mail').click(function() {
        var modifmail = $("#modif_mail");
        if (modifmail.css("display") == 'none')
            modifmail.slideDown();
        else
            modifmail.slideUp();
        });

    $('#modifier_avatar').click(function() {
        var modif_avatar = $("#modif_avatar");
        if (modif_avatar.css("display") == 'none')
            modif_avatar.slideDown();
        else
            modif_avatar.slideUp();
    });
</script>

<?php include 'inc/footer.php'; ?>

</body>
