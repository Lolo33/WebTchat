<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 20/04/2017
 * Time: 05:54
 */
class Commande
{

    private $nom;
    private $description;
    private $react;
    private $rankMin;
    private $nom_fonction;
    private $db;

    public function  __construct($p_db, $p_nom, $p_description, $p_react, $p_rank,$p_fonction){
        $this->db = $p_db;
        $this->nom = $p_nom;
        $this->description = $p_description;
        $this->react = $p_react;
        $this->rankMin = $p_rank;
        $this->nom_fonction = $p_fonction;
    }

    public function existe($message){
        if ($this->cutCmd($message)[0] == $this->react)
            return true;
        return false;
    }

    public function executeCmd($message, $pseudo){
        $func = $this->nom_fonction;
        return $this->$func($message, $pseudo);
    }

    function cutCmd($cmd){
        return explode(' ', $cmd);
    }

    function dureeToSql($duree){
        switch ($duree){
            case '2 heures':
                $interval = "2 HOUR";
                break;
            case '6 heures':
                $interval = "6 HOUR";
                break;
            case '24 heures':
                $interval = "1 DAY";
                break;
            case '7 jours':
                $interval = "7 DAY";
                break;
            case "A vie":
            case "def":
                $interval = "50 YEAR";
                break;
            default:
                $interval = "";
                break;
        }
        return $interval;
    }

    function cmdBan($message, $pseudo) {

        $tab_param = $this->cutCmd($message);
        $nbr_param = count($tab_param) - 1;

        // Si le nombre de paramètres est correct
        if ($nbr_param > 0 && $nbr_param <= 3) {

            // définition du membre à bannir par rapport au 1er paramètre de la commande
            $pseudo_a_bannir = $tab_param[1];
            $req_membre = $this->db->prepare('SELECT * FROM membres WHERE membre_pseudo = :pseudo');
            $req_membre->bindValue(':pseudo', $pseudo_a_bannir, PDO::PARAM_STR);
            $req_membre->execute();
            if ($req_membre->rowCount() > 0)
                $membre_a_bannir = $req_membre->fetch();
            else
                return false;

            // Paramètres par défaut
            $pseudo_banni = $membre_a_bannir["membre_pseudo"];
            $duree_str = "2 heures";
            $interval = $this->dureeToSql($duree_str);
            $raison = "Ban sur commande";
            if ($membre_a_bannir['ip_last_conn'] == NULL) {
                $ip = $membre_a_bannir['ip_inscription'];
            } else {
                $ip = $membre_a_bannir['ip_last_conn'];
            }

            // Si il n(y a qu'1 seul paramètre
            if ($nbr_param > 1) {

                // re-définition de la durée par rapport au 2ème paramètre de la commande
                $duree_str = $tab_param[2];
                if (is_int($duree_str)) {
                    $duree_str = $duree_str . ' Heures';
                    $interval = $duree_str . ' HOUR';
                } elseif ($duree_str == "def") {
                    $duree_str = 'A vie';
                    $interval = $this->dureeToSql($duree_str);
                } else
                    return false;


                if ($nbr_param > 2) {
                    $raison = $tab_param[3];
                }
            }

            $req = $this->db->prepare('INSERT INTO bans (raison, banneur, membre_banni, ban_debut, ban_fin, duree_str, ip) VALUES (?, ?, ?, NOW(), DATE_ADD(NOW(), INTERVAL ' . $interval . '), ?, ?)');
            $req->execute(array($raison, $pseudo, $pseudo_banni, $duree_str, $ip));
            return true;

        }
        return false;
    }

}