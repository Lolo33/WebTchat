<?php
require_once 'inc/conf.php';
$db = getConnexion();
$req = $db->query("SELECT * FROM membres ORDER BY membre_msg DESC LIMIT 20;");
$req->execute();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $site; ?> - Les Membres</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/style2.css" />
    <link href='https://fonts.googleapis.com/css?family=Josefin+Sans|Josefin+Slab' rel='stylesheet' type='text/css'>
    <link rel="icon" type="image/png" href="favicon.png" />
    <script src="js/jquery-1.12.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</head>
<body>


<?php include('inc/nav.php'); ?>

<div class="titre">Les Membres Actifs</div>


<div id="conteneur-index" style="margin-top: 20px; min-height: 420px;">
        <table class="table table-striped table-hover ">
            <thead>
            <tr>
                <th>Pseudo</th>
                <th>Avatar</th>
                <th>Nombre de Messages</th>
                <th>Dernier message:</th>
            </tr>
            </thead>
            <tbody>

            <?php
            while($membre = $req->fetch()) {
                $rank = rankToString($membre['membre_rank']);
                $req2 = $db->prepare('SELECT MAX(msg_date) FROM messages WHERE pseudo = :pseudo ;');
                $req2->bindValue(':pseudo', $membre['membre_pseudo'], PDO::PARAM_STR);
                $req2->execute();
                $dernierMsg = $req2->fetchColumn();
                $req_av = $db->prepare("SELECT * FROM avatars WHERE id = :id");
                $req_av->bindValue(":id", $membre["membre_avatar"], PDO::PARAM_INT);
                $req_av->execute();
                $avatar = $req_av->fetch();
                echo "
                <tr style='cursor: pointer;' class=\"active\" onclick='document.location.replace(\"profil.php?search=" . $membre['membre_pseudo'] . "\");'>               
                    <td>".$membre['membre_pseudo']." </td>
                    <td><img width=\"35px\" src=\"" . $settings['avatar_url'] . DIRECTORY_SEPARATOR . $avatar['url'] . "\" /></td>
                    <td>".$membre['membre_msg']."</td>
                    <td>".$dernierMsg."</td>
                </tr>";
            }
            ?>

            </tbody>
        </table>

    </div>

<?php include 'inc/footer.php'; ?>

</body>
</html>