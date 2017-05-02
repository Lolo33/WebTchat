
<?php
$host_name  = "localhost";
$database   = "tchat";
$user_name  = "root";
$password   = "";
try {
    $db = new PDO('mysql:host=' . $host_name . ';dbname=' . $database . ';charset=utf8', '' . $user_name . '', $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

$setting = $db->query("SELECT * FROM retrotalk_settings");
$setting->execute();
$settings = $setting->fetch(PDO::FETCH_ASSOC);

if ($settings['maintenance'] == 'false'){
    header('Location: index.php');
}
?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Tchat - Maintenance</title>
        <link rel="stylesheet" href="css/style.css" />
        <link rel="stylesheet" href="css/style2.css" />
        <link rel="icon" type="image/png" href="favicon.png" />
        <link href='https://fonts.googleapis.com/css?family=Josefin+Sans|Josefin+Slab' rel='stylesheet' type='text/css'>
        <script src="js/jquery-1.12.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </head>
<body style="background: url('img/mtn.png'); background-size: 100%; background-repeat: no-repeat;">

<div id="conteneur-index" class="panel panel-primary" style="margin-top: 200px;">
    <div class="panel-heading">
        <h3 class="panel-title">Oh Non ! Une maintenance est en cours !</h3>
    </div>
    <div class="panel-body" id="ban">
        <h4>
            Nous sommes d&eacute;sol&eacute;s, mais une maintenance est en cours, nous travaillons sur les points suivants :<br /><br /> <b><?php echo $settings['reason'] ?>.<br><br></b>
            Merci de r&eacute;&eacute;ssayer plus tard !<br>
            <center>- L'&eacute;quipe</center></h4>
    </div>
</div>