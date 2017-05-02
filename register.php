<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <script src="js/jquery-1.12.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="icon" type="image/png" href="favicon.png" />
    <link rel="stylesheet" href="css/style2.css" type="text/css" />
</head>
<body>
<?php
include 'inc/conf.php';
include 'inc/nav.php';
if (isLogged()){
    header('Location: tchat.php');
}
?>

<div style="display: none;" class="ki">
</div>


<div id="conteneur-index" class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Inscris-toi !</h3>
    </div>
    <div class="panel-body">
        <form id="form-register" method="post" class="form-horizontal">
            <fieldset>

                <div class="form-group">
                    <label for="inputPseudo" class="col-lg-2 control-label">Pseudo</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" name="pseudo" maxlength="15" id="inputPseudo" placeholder="Pseudo">
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputPassword" class="col-lg-2 control-label">Mot de passe</label>
                    <div class="col-lg-10">
                        <input type="password" class="form-control" name="pass" id="inputPassword" placeholder="Mot de passe">
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputPassword" class="col-lg-2 control-label">Confirmation</label>
                    <div class="col-lg-10">
                        <input type="password" name="repassword" class="form-control" id="inputRePassword" placeholder="Confirmer mot de passe">
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputMail" class="col-lg-2 control-label">E-mail</label>
                    <div class="col-lg-10">
                        <input type="text" name="mail" class="form-control" id="inputMail" placeholder="E-mail">
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
    $('#form-register').submit(function(){
        var pseudo = $('#inputPseudo').val();
        var mail = $('#inputMail').val();
        var pass = $('#inputPassword').val();
        var repass = $('#inputRePassword').val();
        console.log(pseudo + mail + pass + repass);
        $.post('ajax/reg_check.php', {pseudo:pseudo, mail:mail, pass:pass, repass:repass}, function(data){
            $('.ki').html(data).slideDown();
            console.log(data);
        });

        return false;
    });
</script>
</body>
</html>