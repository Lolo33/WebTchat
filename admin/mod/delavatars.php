<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 19/04/2017
 * Time: 00:15
 */

include '../conf.php';

if (isset($_GET['id']) && !empty($_GET['id'])){
    $id = htmlspecialchars(trim($_GET["id"]));

    $req_verif = $db->prepare('SELECT * FROM membres WHERE membre_avatar = :id');
    $req_verif->bindValue(":id", $id, PDO::PARAM_INT);
    $req_verif->execute();
    if ($req_verif->rowCount() > 0) {
        while ($membre = $req_verif->fetch()) {
            $req_up = $db->prepare('UPDATE membres SET membre_avatar = 1 WHERE membre_id = :id_membre');
            $req_up->bindValue(":id_membre", $membre["membre_id"], PDO::PARAM_INT);
            $req_up->execute();
        }
    }

    $req_img = $db->prepare("SELECT url FROM avatars WHERE id = :id");
    $req_img->bindValue(":id", $id, PDO::PARAM_INT);
    $req_img->execute();
    $ancienne_url = $req_img->fetchColumn();

    $dossier = '../../' . $settings["avatar_url"] . DIRECTORY_SEPARATOR;
    if (file_exists($dossier.$ancienne_url))
        unlink($dossier.$ancienne_url);

    $req = $db->prepare('DELETE FROM avatars WHERE id = :id');
    $req->bindValue(":id", $id, PDO::PARAM_INT);
    $req->execute();
    header("Location: ../avatars.php");
}