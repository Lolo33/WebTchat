<?php ?>
<nav class="navbar navbar-default" style="background: #464545; ">
    <div class="container-fluid">
        <div class="navbar-header" >
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <a class="navbar-brand" href="tchat.php"><?php echo $settings["nom"]; ?></a>

        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

            <ul class="nav navbar-nav" style="">
                <li><a href="regles.php">Les regles</a></li>
                <?php
                if (isLogged())
                    echo '<li><a href="profil.php" >Profil</a></li>';
                ?>
                <li><a href="membres.php">Les Membres</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <?php
                if (isLogged() && $_SESSION['rank'] >= 3)
                     echo '<li id="adm-link" class="adm-link" ><a href="admin" target="_blank" >Administration</a ></li >';
                if (isLogged())
                    echo '<li id="btn-deconnexion"><a href="deco.php">D&eacute;connexion</a></li>';
                else
                    echo '<li><a href="register.php">S\'inscrire</a></li>';
                ?>

            </ul>
        </div>
    </div>
</nav>