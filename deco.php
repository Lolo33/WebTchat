<?php
/**
 * Created by PhpStorm.
 * User: Loïc
 * Date: 01/07/2016
 * Time: 04:46
 */
require_once 'inc/conf.php';
session_destroy();
header('Location: index.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tchat - D&eacute;connexion</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/style2.css" />
    <link href='https://fonts.googleapis.com/css?family=Josefin+Sans|Josefin+Slab' rel='stylesheet' type='text/css'>
    <link rel="icon" type="image/png" href="favicon.ico" />
    <script src="js/jquery-1.12.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <META http-equiv="refresh" content="5;URL=tchat.php">
</head>
<body>

<?php include 'inc/nav.php'; ?>

<div style="display: none;" class="ki">

</div>


<div id="conteneur-index" class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">D&eacute;connexion</h3>
    </div>
    <div class="panel-body">
        Vous etes bien d&eacute;connect&eacute;, vous allez etre redirig&eacute; dans quelques secondes!
    </div>
</div>

<?php include 'inc/footer.php';
