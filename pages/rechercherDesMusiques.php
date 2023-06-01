<?php
session_start();
//empêche d'accéder à cette page par l'url si l'on n'est pas connecté (redirige vers connexion.php)
if (!isset($_SESSION["pseudo"])){
    header('Location: ../connexion.php');
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="author" content="Margaux Barrouillet">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Onzer</title>
  <link rel="stylesheet" type="text/css" href="../css/style_user.css"/>

</head>
  <body>
    <div class="content_top">
      <a href="connexion.php"><p id="onzer">Onzer</p></a>
      <form method="POST" action="../connexion.php">
        <input type="submit" id="bouton_deco" name="OUT" value="déconnexion"/>
      </form>
    </div>
    <div class="content_middle">
      <?php
        echo "<p id='message_connexion'>Connecté en tant que : ".$_SESSION["pseudo"].".</p>";
      ?>
      <form class="afficher_infos" action="" method="POST" onsubmit="afficherInfosArtiste(event,this['nom'].value)">
        <input required="required" type="text" id="searchInput" name="nom" placeholder="Nom de l'artiste" >
      <input type="submit" name="submit" id="submit" value="Rechercher" >
      </form>
      <div id="infosArtiste"></div>
    </div>
    <script>

    function afficherInfosArtiste(event, nom) {
      event.preventDefault();

      //recup l'élément HTML pour afficher les informations
      var elementInfo = document.getElementById("infosArtiste");
      elementInfo.innerHTML = "";

      //enlever les espaces (notamment à la fin)
      nom = nom.trim();
      nom = nom.toLowerCase();
      nom = nom.replace(/\s+/g, '');


      //requête fetch pour obtenir les données du fichier CSV
      fetch('../csv/' + nom + '.csv')
        .then(function(response) {
          return response.text();
        })
        .then(function(data) {
          //séparer les lignes du fichier CSV
          var lignes = data.split('\n');

          //on parcourt les lignes et on affiche les informations
          lignes.forEach(function(ligne) {
            //on ignore les lignes vides ou mal formatées
            if (ligne.trim() !== "") {
              var infos = ligne.split(';');
              var titre = infos[0];
              var duree = infos[1];

              //affichage des informations s'il y a des titres trouvés
              if (titre && duree) {
                elementInfo.innerHTML += "<p>Titre : " + titre + ", Durée : " + duree + " sec</p>";
              } else {
                elementInfo.innerHTML = "Aucun titre trouvé.";
              }
            }
          });
        })
        .catch(function(error) {
          console.error('Erreur lors de la récupération des données', error);
        });
    }
    </script>

  </body>
</html>
