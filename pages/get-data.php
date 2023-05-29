<?php
  $data = array();
  if (file_exists('infoMusiciens.csv')) {
    $file = fopen('infoMusiciens.csv', 'r');
    while (($line = fgetcsv($file, 0, ';')) !== false) {
      $data[] = array(
        'nom' => $line[0],
        'date' => $line[1],
        'nb_albums' => $line[2]
      );
    }
    fclose($file);
  }
  
  echo json_encode($data);
?>

