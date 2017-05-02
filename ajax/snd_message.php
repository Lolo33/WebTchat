<?php
/**
 * Created by PhpStorm.
 * User: Lo�c
 * Date: 01/07/2016
 * Time: 05:34
 */

require_once '../inc/conf.php';
include '../inc/Commande.php';

if (!isLogged()){
    header('Location: index.php');
}
if (isBan()){
    '<script>document.location.replace("index.php");</script>';
}

if (isset($_POST['message'])){
    $message = htmlspecialchars(trim($_POST['message']));
    $chaineSpace = explode(' ', $message);
    if (strlen($chaineSpace['0']) > 70){
        errChat("Vous ne pouvez pas &eacute;crire autant de lettres d'affil&eacute; !");
    }else {

        // Détection et gestion de commandes par rapport au message
        $req_commandes = $db->prepare("SELECT * FROM commandes WHERE rankMin <= :rank");
        $req_commandes->bindValue(":rank", $_SESSION["rank"], PDO::PARAM_INT);
        $req_commandes->execute();
        $cmd = false;
        while ($commandes = $req_commandes->fetch()) {
            $uneCommande = new Commande($db, $commandes['nom'], $commandes['description'], $commandes['react'], $commandes['rankMin'], $commandes["function"]);
            if ($uneCommande->existe($message)) {
                $cmd = true;
                if ($uneCommande->executeCmd($message, $_SESSION['pseudo']))
                    succesChat('La commande a bien été executée.');
                else
                    errChat("Une erreur est survenue avec la commande");
                break;
            }
        }

        // Si le message n'est pas une commande
        if (!$cmd) {

            // On ajoute le message à la bdd + update le nbr de messages de l'utilisateur
            $req1 = $db->prepare('INSERT INTO messages (pseudo, contenu, msg_date) VALUES (:pseudo, :content, NOW())');
            $req1->bindParam(':pseudo', $_SESSION['pseudo'], PDO::PARAM_STR);
            $req1->bindParam(':content', $message, PDO::PARAM_STR);
            $req1->execute();
            $req2 = $db->prepare('UPDATE membres SET membre_msg = membre_msg + 1 WHERE membre_pseudo = :id');
            $req2->bindValue(':id', $_SESSION['pseudo'], PDO::PARAM_INT);
            $req2->execute();

        }
    }
}else{
    errChat("Le serveur n'a pas recu votre message, veuillez r&eacute;essayer");
}