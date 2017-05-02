<?php
/**
 * Created by PhpStorm.
 * User: Lo�c
 * Date: 11/07/2016
 * Time: 04:23
 */

include('inc/conf.php');
?>
<!DOCTYPE html>
<head>
    <style>
        #textAre { margin-left:; }
    </style>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="stylesheet" href="css/style2.css" type="text/css">
    <link rel="icon" type="image/png" href="favicon.png" />
    <script src="js/jquery-1.12.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <title><?php echo $site ?> - Contact</title>
    <?php include('inc/nav.php'); ?>
</head>
<body>
<a href="#" class="btn btn-default btn-lg btn-block" id="contact-direct">Contacter directement sur le site</a><br>
<div class="panel panel-danger" style="display:none;" id="contact-contenu">
    <div class="panel-heading">
        <h3 class="panel-title">Contact sur le site</h3>
    </div>
    <div class="panel-body">
        <label class="control-label" for="sujet">Sujet de la demande d'aide</label>
        <input class="form-control" id="sujet" type="text"><br>
        <label for="demande" class="col-lg-2">Posez votre question</label><br>
        <div class="col-lg-10">
            <textarea class="form-control" rows="3" id="textArea" style="margin-left:-232px;"></textarea><br>
            <button type="button" class="btn btn-primary" style="margin-left:-232px;">Envoyer</button>
            <div class="alert alert-dismissible alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Attention !</strong> <a href="#" class="alert-link">Merci de ne pas abuser ou de spammer les staffs via le support de contact
            </div>
        </div>
    </div>
</div>
<a href="#" class="btn btn-default btn-lg btn-block" id="contact-direct-fb">Contacter via Facebook</a><br>
<div class="alert alert-dismissible alert-danger" id="contact-contenu-fb" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Attention !</strong> <div class="alert-link">Merci de ne pas abuser ou de spammer les staffs via le support de contact.<br> Merci de contacter ... sur Facebook</div></div>



</body>
<?php include('inc/footer.php'); ?>

<script>
    $('#contact-direct').click(function() {
        if ($("#contact-contenu").css("display") == 'none')
            $("#contact-contenu").show()
        else
            $("#contact-contenu").hide();
    });

    $('#contact-direct-fb').click(function() {
        if ($("#contact-contenu-fb").css("display") == 'none')
            $("#contact-contenu-fb").show()
        else
            $("#contact-contenu-fb").hide();
    });
</script>