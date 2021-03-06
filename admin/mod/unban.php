                                                                                            <?php
    require_once '../conf.php';
    if (isset($_POST['psd'])) {
        $pseudo = $_POST['psd'];
        $req_membre = $db->prepare('SELECT * FROM membres WHERE membre_pseudo = :pseudo');
        $req_membre->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
        $req_membre->execute();
        $membre = $req_membre->fetch();
        if ($membre == NULL){
            errPseudoNexistePas();
        }
        $req = $db->prepare('DELETE FROM bans WHERE membre_banni = :pseudo');
        $req->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
        $req->execute();
        header('Location: ../ban.php');
    }else{
        header('Location: ../ban.php');
    }
?>