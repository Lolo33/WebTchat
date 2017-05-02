<?php /**
 * Created by PhpStorm.
 * User: Lo�c
 * Date: 30/06/2016
 * Time: 10:07
 */
require_once 'inc/conf.php';
if (isLogged()){
     header( 'Location: tchat.php' ) ;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tchat - Connexion</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/style2.css" />
    <link rel="icon" type="image/png" href="favicon.png" />
    <link href='https://fonts.googleapis.com/css?family=Josefin+Sans|Josefin+Slab' rel='stylesheet' type='text/css'>
    <script src="js/jquery-1.12.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</head>
<body>

<?php include 'inc/nav.php'; ?>


<div style="display: none;" class="ki">

</div>


<div id="conteneur-index" class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Connectes-toi !</h3>
    </div>
    <div class="panel-body">
        <form id="form-connexion" method="post" class="form-horizontal">
            <fieldset>

                <div class="form-group">
                    <label for="pseudo" class="col-lg-2 control-label">Email / Pseudo</label>
                    <div class="col-lg-10">
                        <input type="text" id="pseudo" class="form-control" name="pseudo" placeholder="Email">
                    </div>
                </div>

                <div class="form-group">
                    <label for="pass" class="col-lg-2 control-label">Mot de Passe</label>
                    <div class="col-lg-10">
                        <input type="password" id="pass" class="form-control" name="pass" placeholder="Mot de passe">
                        <div class="checkbox">
                            <label style="width: 50%; display: inline-block">
                                <input type="checkbox">Rester Connect&eacute;
                            </label>
                            <label style="width: 48%; text-align: right; display: inline-block;">
                                 <a href="register.php">Inscris-toi</a>
                            </label>
                        </div>
                    </div>
                </div>

                <div style="text-align: right;" class="form-group">
                    <div class="col-lg-10 col-lg-offset-2">
                        <button type="reset" class="btn btn-default">Annuler</button>
                        <button name="conn" type="submit" class="btn btn-primary">Se Connecter</button>
                    </div>
                </div>

            </fieldset>
        </form>
    </div>
</div>

<?php include 'inc/footer.php'; ?>

<script>
    $('#form-connexion').submit(function(){
        var pseudo = $('#pseudo').val();
        var pass = $('#pass').val();
        $.post('ajax/check.php', {pseudo:pseudo, pass:pass}, function(data){
            $('.ki').html(data).slideDown();
            console.log(data);
        });

        return false;
    });
</script>

</body>
