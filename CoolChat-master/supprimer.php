<?php
// paramètres de connexion a la bd
$dsn = 'mysql:host=localhost;dbname=coolchat';
$username = 'root';
$password = 'karim34500';

// Options de configuration pour la connexion PDO
$options = array(
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
);

try {
  // connexion à la bd
  $dbh = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
  // sinon message d'erreur
  die('Une erreur est survenue lors de la connexion à la base de données.');
}

// Vérifie si un l'id  a été passé en paramètre
if (!isset($_GET['id'])) {
  // sinon
  header('Location: compte.php');
  exit;
}

// Récupére l'id
$id = $_GET['id'];

// Vérifier si l'utilisateur existe 
$sql = "SELECT * FROM Inscrit WHERE email = :email";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':email', $id);
$stmt->execute();
$inscrit = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$inscrit) {
  // Sinon
  header('Location: compte.php');
  exit;
}
// Vérifier si le formulaire de confirmation de suppression a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Préparer la requête SQL pour supprimer 
  $sql = "DELETE FROM Inscrit WHERE email = :email";
  $stmt = $dbh->prepare($sql);
  $stmt->bindParam(':email', $id);

  // Exécuter la requête SQL
  $stmt->execute();

  // Redirige
  header('Location: index.php');
  exit;
}
?>
<!doctype html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php include_once('startsession.php'); ?>
  <link rel="stylesheet" href="./css/supprimer.css">
  <title>Succulent Illustration using HTML and CSS - Coding Torque</title>
</head>

<body>
<header>
    <img src="./image/logoCC.png" class="logo">
    <h1>Êtes-vous sûr de vouloir nous quitter</h1>
    <form method="POST">
      <input type="hidden" name="id" value="<?= $id ?>">
      <input type="submit" value="Oui">
      <input type="submit" value="Non" formaction="index.php">
    </form>
  </header>
  <div>



  </div>
  <div class="george">
    <div class="shadow"></div>
    <div class="george_flower"></div>
    <div class="george_head">
      <div class="line"></div>
      <div class="cheek"></div>
      <div class="eye"></div>
      <div class="eye"></div>
    </div>
    <div class="pot_top"></div>
    <div class="pot_body"></div>
    <div class="pot_plate"></div>
  </div>

</body>

</html>