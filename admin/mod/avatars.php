<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18/04/2017
 * Time: 23:13
 */

include '../conf.php';

if (isset($_POST["submit"])){
    $sexe = htmlspecialchars(trim($_POST["genre"]));
    $is_vip = htmlspecialchars(trim($_POST["isvip"]));

    if (extension_is_correct($_FILES['avatar']['name'])) {

        $dossier = '../../' . $settings["avatar_url"] . DIRECTORY_SEPARATOR;
        $fichier = basename($_FILES['avatar']['name']);
        $url = date('d-m-Y_H-i-s') . '_' . $fichier;

        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $dossier . $url)) {
            $req = $db->prepare('INSERT INTO avatars (url, is_vip, sexe) VALUES ( :url, :vip, :sex )');
            $req->bindParam(":url", $url, PDO::PARAM_STR);
            $req->bindParam(":vip", $is_vip, PDO::PARAM_INT);
            $req->bindParam(":sex", $sexe, PDO::PARAM_STR);
            $req->execute();
            header("Location: ../avatars.php");
        } else {
            echo 'Echec de l\'upload !';
        }

    }else{
        echo 'Vous devez uploader un fichier de type png, gif, jpg, jpeg, txt ou doc...';
    }

}else{
    header("Location: ../avatars.php");
}