<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <meta name="author" content="Margaux Barrouillet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Onzer</title>
    <link rel="stylesheet" type="text/css" href="css/style_connexion.css"/>
  </head>
  <body>
  <!--si déconnexion détruit la session-->
  <?php
    if (isset($_POST["OUT"])){
      session_destroy();
    }
  ?>
  <div class="content_top">
    <a href="connexion.php"><p id="onzer">Onzer</p></a>
  </div>
  <div class="content_middle">
    <h1>Connexion</h1>
    <form class="connexion" action="pages/verificationConnexion.php" method="POST">
      <input required="required" type="text" id="pseudo" name="pseudo" placeholder="Pseudo">
      <input required="required" type="password" id="mdp" name="mdp" placeholder="Mot de passe">
    <input type="submit" name="submit" id="connexion" value="Connexion">
    </form>
  </div>
  </body>
</html>
