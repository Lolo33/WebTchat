<?php
/**
 * Created by PhpStorm.
 * User: Loïc
 * Date: 05/07/2016
 * Time: 15:33
 */
require_once '../conf.php';
$db = getConnexion();
$req = $db->query('DELETE FROM messages;');
$req->execute();
$req2 = $db->query('UPDATE membres SET membre_msg = 0;');
$req2->execute();
header('Location: ../index.php');