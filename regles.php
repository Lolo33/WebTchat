<?php
include('inc/conf.php');
?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="stylesheet" href="css/style2.css" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Josefin+Sans|Josefin+Slab' rel='stylesheet' type='text/css'>
    <link rel="icon" type="image/png" href="favicon.png" />
    <script src="js/jquery-1.12.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <title><?php echo $site ?> - Les r&egrave;gles</title>
    <?php include('inc/nav.php'); ?>
</head>
<body>

<div class="titre">Les R&egrave;gles</div>

<div class="panel panel-primary" id="conteneur-index">
    <div class="panel-heading">
        <h3 class="panel-title">Les R&egrave;gles &agrave; appliquer sur <?php echo $site; ?></h3>
    </div>
    <div class="panel-body" style="padding: 30px;">
        <strong>S&eacute;curit&eacute; et Confidentialit&eacute;</strong>
        <ul class="regle-cat">
            <li>Ne communiques jamais tes informations personnelles sur le site, aucun membre du staff ne te demanderas de lui communiquer ton mot de passe, num. de cb... Demander de telles informations a un utilisateur est passible de banissement &agrave; vie.</li>
            <li>&Eacute;vites de cliquer sur les liens que t'envoient les autres utilisateurs, surtout quand ceux-ci ne te disent absolument rien. Certains sites sont malveillant pour vos informations et votre ordinateur</li>
            <li>N'essayes jamais de te faire passer pour quelqu'un d'autre ou d'obtenir des informations qui ne te sont pas d&eacute;di&eacute;es. La fraude et le piratage sont s&eacute;v&egrave;rement sanctionn&eacute;s sur <?php echo $site; ?>. </li>
            <li>N'envoies pas des tonnes de messages inutilement sur le tchat. Le spam est nuisible pour <?php echo $site; ?> et &agrave; ses utilisateurs. Le ratio maximal de messages tol&eacute;r&eacute; est de <span class="nb">10 messages / minute</span> et <span class="nb">75 messages / heure</span> pour chaque utilisateur. (&Eacute;vitez au maximum d'atteindre ce ratio!)</li>
        </ul>
    </div>
</div>
<?php
    include('inc/footer.php');
?>
</body>
