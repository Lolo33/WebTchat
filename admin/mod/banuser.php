<?php
/**
 * Created by PhpStorm.
 * User: Lo�c
 * Date: 06/07/2016
 * Time: 02:01
 */

include '../conf.php';

if (isset($_POST['subm'])){
    if (!empty($_POST['pseudo']) && !empty($_POST['raison'])) {
        $pseudo = htmlspecialchars(trim($_POST['pseudo']));
        $duree = $_POST['duree'];
        $raison = htmlspecialchars(trim($_POST['raison']));
        $interval = dureeToSql($duree);

        //$requete = $db->prepare("DELETE FROM bans WHERE ban_fin < NOW();");
        //$requete->execute();

        $req_membre = $db->prepare('SELECT * FROM membres WHERE membre_pseudo = :pseudo');
        $req_membre->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
        $req_membre->execute();
        $membre_a_bannir = $req_membre->fetch();

        if ($membre_a_bannir == NULL){
            errPseudoNexistePas();
        }else {
            if ($membre_a_bannir['membre_rank'] == $_SESSION['rank']) {
                echo '<script>
            alert("ERREUR: Vous ne pouvez pas bannir un membre qui a le meme rang que vous ou +");
            document.location.replace("../index.php");
            </script>';
            } else {
                if ($membre_a_bannir['ip_last_conn'] == NULL) {
                    $ip = $membre_a_bannir['ip_inscription'];
                } else {
                    $ip = $membre_a_bannir['ip_last_conn'];
                }
                $req = $db->prepare('INSERT INTO bans (raison, banneur, membre_banni, ban_debut, ban_fin, duree_str, ip) VALUES (?, ?, ?, NOW(), DATE_ADD(NOW(), INTERVAL ' . $interval . '), ?, ?)');
                $req->execute(array($raison, $_SESSION['pseudo'], $pseudo, $duree, $ip));
                header('Location: ../ban.php');
            }
        }
    }else{
        errChmpsVides();
    }
}else{
    errFormulaire();
}
