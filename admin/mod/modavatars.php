<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18/04/2017
 * Time: 23:54
 */

include '../conf.php';

if (isset($_POST["submit"]) && isset($_GET['id'])) {
    $id = htmlspecialchars(trim($_GET['id']));
    $sexe = htmlspecialchars(trim($_POST["genre"]));
    $is_vip = htmlspecialchars(trim($_POST["isvip"]));

    if ($_FILES['avatar']['name'] == null){
        $req = $db->prepare('UPDATE avatars SET is_vip = :vip, sexe = :sex WHERE id = :id');
        $req->bindParam(':vip', $is_vip, PDO::PARAM_INT);
        $req->bindParam(":sex", $sexe, PDO::PARAM_STR);
        $req->bindParam(":id", $id, PDO::PARAM_INT);
        $req->execute();
        header("Location: ../avatars.php");
    }else{

        if (extension_is_correct($_FILES['avatar']['name'])) {

            $dossier = '../../' . $settings["avatar_url"] . DIRECTORY_SEPARATOR;
            $fichier = basename($_FILES['avatar']['name']);
            $url = date('d-m-Y_H-i-s') . '_' . $fichier;

            // Supprime l'ancienne image si elle existe encore
            $req_img = $db->prepare("SELECT url FROM avatars WHERE id = :id");
            $req_img->bindValue(":id", $id, PDO::PARAM_INT);
            $req_img->execute();
            $ancienne_url = $req_img->fetchColumn();

            if (file_exists($dossier . $ancienne_url))
                unlink($dossier . $ancienne_url);

            // Ajoute les informations si l'image a bien été chargée sur le ftp
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $dossier . $url)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
            {
                $req = $db->prepare('UPDATE avatars SET is_vip = :vip, sexe = :sex, url = :url WHERE id = :id');
                $req->bindParam(':vip', $is_vip, PDO::PARAM_INT);
                $req->bindParam(":sex", $sexe, PDO::PARAM_STR);
                $req->bindParam(":url", $url, PDO::PARAM_STR);
                $req->bindParam(":id", $id, PDO::PARAM_INT);
                $req->execute();
                header("Location: ../avatars.php");
            } else {
                echo 'Echec de l\'upload !';
                header("Location: ../avatars.php");
            }
        }
    }

}