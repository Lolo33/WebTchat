<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 19/04/2017
 * Time: 15:39
 */

include "../conf.php";

if (!empty($_POST)) {

    function react_exist($db, $react)
    {
        $req = $db->prepare('SELECT * FROM emoticones WHERE icon_react = :react');
        $req->bindValue(':react', $react, PDO::PARAM_STR);
        $req->execute();
        if ($req->rowCount() > 0)
            return true;
        return false;
    }

    $vip = htmlspecialchars(trim($_POST["isvip"]));
    $react = htmlspecialchars(trim($_POST["react"]));
    $dossier = '../../' . $settings["emote_url"] . DIRECTORY_SEPARATOR;

    if (isset($_POST["add"])) {
        if (!react_exist($db, $react)) {
            if (extension_is_correct($_FILES['emote']['name'])) {

                $fichier = basename($_FILES['emote']['name']);
                $url = date('d-m-Y_H-i-s') . '_' . $fichier;

                if (move_uploaded_file($_FILES['emote']['tmp_name'], $dossier . $url)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
                {
                    $req = $db->prepare("INSERT INTO emoticones (icon_url, icon_react, icon_is_vip) VALUES (:url, :react, :isvip)");
                    $req->bindValue(":url", $url, PDO::PARAM_STR);
                    $req->bindValue(":react", $react, PDO::PARAM_STR);
                    $req->bindValue(":isvip", $vip, PDO::PARAM_INT);
                    $req->execute();
                    header('Location: ../emotes.php');
                } else {
                    //echo 'Echec de l\'upload !';
                    //header("Location: ../emotes.php");
                }

            }
        }
    } elseif (isset($_POST["mod"])) {

        $id = htmlspecialchars(trim($_GET["id"]));

        if ($_FILES['emote']['name'] != null) {
            if (extension_is_correct($_FILES['emote']['name'])) {

                $fichier = basename($_FILES['emote']['name']);
                $url = date('d-m-Y_H-i-s') . '_' . $fichier;

                if (move_uploaded_file($_FILES['emote']['tmp_name'], $dossier . $url)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
                {

                    $req_img = $db->prepare("SELECT icon_url FROM emoticones WHERE icon_id = :id");
                    $req_img->bindValue(":id", $id, PDO::PARAM_INT);
                    $req_img->execute();
                    $ancienne_url = $req_img->fetchColumn();
                    if (file_exists($dossier . $ancienne_url))
                        unlink($dossier . $ancienne_url);
                    $req = $db->prepare("UPDATE emoticones SET icon_url = :url, icon_react = :react, icon_is_vip = :isvip WHERE icon_id = :id");
                    $req->bindValue(":url", $url, PDO::PARAM_STR);
                    $req->bindValue(":react", $react, PDO::PARAM_STR);
                    $req->bindValue(":isvip", $vip, PDO::PARAM_INT);
                    $req->bindValue(":id", $id, PDO::PARAM_INT);
                    $req->execute();
                    header('Location: ../emotes.php');
                } else {
                    echo 'Echec de l\'upload !';
                    //header("Location: ../emotes.php");
                }
            } else {
                echo 'ERREUR ! votre fichier doit porter l\'extension .jpg, .jpeg, .png, .bmp, .ico';
            }
        } else {
            $req = $db->prepare("UPDATE emoticones SET icon_react = :react, icon_is_vip = :isvip WHERE icon_id = :id");
            $req->bindValue(":react", $react, PDO::PARAM_STR);
            $req->bindValue(":isvip", $vip, PDO::PARAM_INT);
            $req->bindValue(":id", $id, PDO::PARAM_INT);
            $req->execute();
            header('Location: ../emotes.php');
        }
    } else {

    }
}else{

}