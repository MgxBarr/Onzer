<?php
session_start();
?>
<?php
$verif=false;
if (($handle = fopen("identifiants.csv", "r")) !== FALSE) {
    while ((($data = fgetcsv($handle, 1000, ";")) !== FALSE) && !$verif) {
        if ($_POST['pseudo']==$data[0] && $_POST['mdp']==$data[1]){
             $verif=true;
             $_SESSION["pseudo"]=$data[0];
             $_SESSION["mdp"]=$data[1];
             $_SESSION["type"]=$data[2];
        }
    }
    fclose($handle);
}

if ($_SESSION["type"]=="administrateur"){
    $file = fopen("identifiants.csv", "r");
    $dictionnaire = array(); 
    $entete = fgetcsv($file);
    while (($ligne = fgetcsv($file)) !== FALSE) {
        $ligne_dictionnaire = array();
        for ($i = 0; $i < count($entete); $i++) {
            $ligne_dictionnaire[$entete[$i]] = $ligne[$i];
        }
        $dictionnaire[] = $ligne_dictionnaire;
    }
fclose($file); 
}

if (!$verif){
      header('Location: ../connexion.php');
      exit ();
}
if (($verif) and ($_SESSION["type"]=="administrateur") ){
  header('Location: accueil_admin.php');
  exit ();
}
if (($verif) and ($_SESSION["type"]=="utilisateur") ){
    header('Location: rechercherDesMusiques.php');
    exit ();
}
?>
