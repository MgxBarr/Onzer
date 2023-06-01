<?php
//empêcher d'accéder à cette page si pas connecté en tant qu'admin
session_start();
if (!isset($_SESSION["pseudo"]) || $_SESSION["type"] !== "administrateur") {
    header('Location: ../connexion.php');
}


//--------------------------------------------------------------------------------------------------------------------------//
//dans la méthode du form on envoie les infos sur cette même page (utilisation de modals pour avoir moins de pages)         //
//selon le form qui est remplit (musicien ou titre) on appelle la fonction correspondante pour ajouter les infos dans le csv//
//--------------------------------------------------------------------------------------------------------------------------//
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['submit1'])) {
    // le formulaire pour ajouter un musicien a été soumis
    ajout_musicien();
    $message = "1";
  }
  if (isset($_POST['submit2'])) {
    // le formulaire pour ajouter un titre a été soumis
    ajout_titre();
    $message = "2";
  }
}

function ajout_musicien(){
  $musicien = array(
    array($_POST["nom"], $_POST["date"], $_POST["nb_albums"])
  );

  $file = fopen("infoMusiciens.csv", "a+");

  foreach ($musicien as $line) {
    fputcsv($file,$line,";");
  }

  fclose($file);

  //créer fichier csv de l'artiste
  $nom = $_POST["nom"];
  $nom=strtolower($nom);
  $nom=preg_replace("/[^a-zA-Z0-9]/", "", $nom);
  $filename = "../csv/" . $nom . ".csv";
  $file2 = fopen($filename, "w");
  fclose($file2);
}

function ajout_titre(){
  $titre = array(
    array($_POST["titre"], $_POST["duree"]),
  );

  $nom = $_POST["nom"];
  echo '<input type="hidden" id="nom" value="' . $nom . '">';
  $nom=strtolower($nom);
  $nom=preg_replace("/[^a-zA-Z0-9]/", "", $nom);
  $post_nom = "../csv/" . $nom . ".csv";
  $filename = __DIR__ . '/' . $post_nom;

  //créer un input caché pour récupérer le nom du fichier plus tard (affichage des titres ajoutés)
  $nomFichier = basename($filename);
  $nomFichier = "../csv/" . $nomFichier;
  echo '<input type="hidden" id="nom_fichier" value="' . $nomFichier . '">';
  
  $file = fopen($filename, "a+");
  foreach ($titre as $line) {
    fputcsv($file,$line,";");
  }

  fclose($file);
}
?>
<script>
//fonction pour lire le csv et afficher les infos 
//prend en paramètre le nom du fichier dont on veut afficher les infos 
// et un id qui vaut 1 ou 2 pour savoir s'il s'agit d'afficher les infos musiciens ou bien titres
function readCSV(file,id) {
  fetch(file)
    .then(response => response.text())
    .then(data => {
      //divise le contenu du fichier CSV en lignes
      const lines = data.split('\n');

      //stocke les éléments de liste à afficher dans une variable
      let listItems = '';

      //affiche le nom de l'artiste/groupe 
      var nom = document.getElementById('nom').value; 

      //parcourt chaque ligne et récupère la première colonne
      for (let i = 0; i < lines.length - 1; i++) {
        const columns = lines[i].split(';');
        const firstColumn = columns[0].replace(/"/g, ''); //supprime les guillemets autour de la valeur

        //ajoute l'élément de liste à la variable
        listItems += '<li>' + firstColumn + '</li>';
      }

      //construit la liste avec les éléments de liste
      const list = '<ul>' + listItems + '</ul>';

      //affecte la liste à la div
      var div = document.getElementById('text-modal'+id);
      div.innerHTML = nom + "<br>"+ list;
    })
    .catch(error => {
      console.error("Une erreur s'est produite lors de la lecture du fichier CSV:", error);
    });
}
</script>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="author" content="Margaux Barrouillet">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Onzer</title>
  <link rel="stylesheet" type="text/css" href="../css/style_admin.css"/>
</head>
  <body>
    <div class="content_top">
      <a href="../connexion.php"><p id="onzer">Onzer</p></a>
      <form method="POST" action="../connexion.php">
        <input type="submit" id="bouton_deco" name="OUT" value="déconnexion"/>
      </form>
    </div>
    <div class="content_middle">
      <?php
        echo "<h1>Profil Admin</h1>";
        echo "<p id='text'>Connecté en tant que : ".$_SESSION["pseudo"].".</p>";
      ?>

      <!--1-->
      <!--modal pour ajouter un artiste/groupe-->
      <div class="box" id="box1" onclick="openmodal(this)">
        <p class="titre-box">Ajouter un artiste/groupe</p>
      </div>
      <div id="modal-box1" class="modal">
          <div class="modal-content">
              <span id="close-box1" class="close">&times;</span>
              <h1 class="title-modal">Ajouter un artiste/groupe</h1>
              <div class="text-modal">
                <form class="ajout_musicien" action="" method="POST">
                  <input required="required" type="text" id="nom" name="nom" placeholder="Nom de l'artiste"><br>
                  <input required="required" type="text" id="date" name="date" placeholder="Date de formation"><br>
                  <input required="required" type="text" id="nb_albums" name="nb_albums" placeholder="Nombre d'albums"><br>
                <input type="submit" name="submit1" id="submit" value="Sauvegarder">
                </form>
              </div>
          </div>
      </div>

      <!--1bis-->
      <!--modal qui n'est pas display par défaut, s'affiche quand on a rempli et envoyé le form d'ajout d'un musicien-->
      <div class="box" id="box3" onclick="openmodal(this)">
        <p class="titre-box">Ajouter un titre</p>
      </div>
      <div id="modal-box3" class="modal">
          <div class="modal-content">
              <span id="close-box3" class="close">&times;</span>
              <h1 class="title-modal">Artistes ajoutés</h1>
              <div class="text-modal" id="text-modal1">
              
              </div>
          </div>
      </div>

      <!--2-->
      <!--modal pour ajouter un titre-->
      <div class="box" id="box2" onclick="openmodal(this)">
        <p class="titre-box">Ajouter un titre</p>
      </div>
      <div id="modal-box2" class="modal">
          <div class="modal-content">
              <span id="close-box2" class="close">&times;</span>
              <h1 class="title-modal">Ajouter un titre</h1>
              <div class="text-modal">
                <form class="ajout_titre" action="" method="POST">
                  <!--suggestions pour chercher un artiste--> 
                  <input required="required" type="text" id="searchInput" name="nom" placeholder="Nom de l'artiste" >
                  <div id="searchResults"></div>
                  <!---->
                  <input required="required" type="text" id="titre" name="titre" placeholder="Titre"><br>
                  <input required="required" type="text" id="duree" name="duree" placeholder="Durée (en sec)"><br>
                <input type="submit" name="submit2" id="submit" value="Sauvegarder">
                </form>
              </div>
          </div>
      </div>
      

      <!--2bis-->
      <!--modal qui n'est pas display par défaut, s'affiche quand on a rempli et envoyé le form d'ajout d'un titre-->
      <div class="box" id="box4" onclick="openmodal(this)">
        <p class="titre-box">Titres ajoutés</p>
      </div>
      <div id="modal-box4" class="modal">
          <div class="modal-content">
              <span id="close-box4" class="close">&times;</span>
              <h1 class="title-modal">Titres ajoutés</h1>
              <div class="text-modal" id="text-modal2">
              </div>
          </div>
      </div>

      
      <script> 
      //fonction pour ouvrir un modal
      function openmodal(m) {
        document.body.classList.add("scrolling")
        var modal=document.getElementById("modal-"+m.id);
        modal.style.display="flex"; //block

        //ferme le modal si on clique sur sa croix
        document.getElementById("close-"+m.id).onclick = function() {
            modal.style.animation = "unloading 0.7s";
            setTimeout(() => {  modal.style.display = "none", modal.style.animation="loading 0.7s", document.body.classList.remove("scrolling")}, 650);
        }

        //ferme le modal si on clique en dehors
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.animation = "unloading 0.7s";
                setTimeout(() => {  modal.style.display = "none", modal.style.animation="loading 0.7s",  document.body.classList.remove("scrolling")}, 650);
            }
        }

        // Appel de la fonction readCSV pour le modal correspondant (pour afficher les infos des musiciens/titres)
        if (m.id === "box3") {
          readCSV('infoMusiciens.csv', 1);
        } else if (m.id === "box4") {
          var csvFilename = document.getElementById("nom_fichier").value; 
          readCSV(csvFilename, 2);
        }
      }

      //affichage des artistes quand on clique sur l'input "nom de l'artiste" dans ajouter un titre
      fetch('get-data.php')
      .then(function(response) {
        return response.json();
      })
      .then(function(data) {
        //traiter les données à l'intérieur car la requête est asynchrone
        // récupérer la recherche de l'utilisateur
        const searchInput = document.getElementById('searchInput');

        //chaque fois qu'une touche est tapée ou lors du focus sur l'input
        searchInput.addEventListener('keyup', showSuggestions);
        searchInput.addEventListener('focus', showSuggestions);

        function showSuggestions() {
          //valeur de l'entrée (input)
          const input = searchInput.value.toLowerCase();

          let result = [];

          if (input !== '') {
            result = data.filter(item => {
              const artistName = item.nom.toLowerCase();
              return input.split('').every((letter, index) => artistName[index] === letter);
            });
          }else {
            result = data;
          }

          let suggestion = "";

          if (result.length > 0) {
            //display des artistes qui correspondent
            result.forEach(resultItem =>
              suggestion += `
              <div class="suggestion" id="${resultItem.nom}" onclick="selectSuggestion(this)" style="display: block;">${resultItem.nom}</div>`
            );
          } else {
            //si aucun artiste trouvé 
            suggestion = `<div class="suggestion">Aucun artiste correspondant</div>`;
          }
          document.getElementById('searchResults').innerHTML = suggestion;
        }
      })
      .catch(function(error) {
        console.error('Erreur lors de la récupération des données', error);
      });

      //fonction appelée lors du clic sur une suggestion ou sur entrée 
      function selectSuggestion(suggestionElement) {
        const searchInput = document.getElementById('searchInput');
        searchInput.value = suggestionElement.innerHTML;

        //enlever les suggestions en dessous de l'input
        const searchResults = document.getElementById('searchResults');
        searchResults.innerHTML = '';
      }
      </script>

      <!--afficher le modal avec les infos des artistes ou titres quand le form correspondant est envoyé-->
      <?php if ($message == "1") :?>
        <script>
        var id_modal = document.getElementById("box3"); 
        openmodal(id_modal);
        </script>
      <?php endif; ?>
      <?php if ($message == "2") :?>
        <script>
        var id_modal = document.getElementById("box4"); 
        openmodal(id_modal); 
        </script>
      <?php endif; ?>
    </div>
  </body>
</html>
