<?php
session_start();

// On se connecte à MySQL avec PDO
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
    die('Error : ' . $e->getMessage());
}

// Vérifie si les variables existent.
if (isset($_POST['pseudo']) && isset($_POST['password'])) {
    // Échappe les caractères spéciaux dans les variables.
    $pseudo = $dbh->quote($_POST['pseudo']);
    $password = $dbh->quote($_POST['password']);
    // Tente de sélectionner une entrée dans la base de données correspondante.
    $sql = "SELECT * FROM Inscrit WHERE pseudo = $pseudo AND password = $password";
    $stmt = $dbh->query($sql);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
        error();
    } else {
        // Si la connexion est réussie, on enregistre le pseudo de l'utilisateur dans une variable de session
        $_SESSION['user'] = $result;
        unset($_SESSION['user']['password']);
        // Redirection
        header('Location: accueil.php');
        exit;
    }
}

function error()
{
?>
    <font color="red">Erreur : identifiant ou mot de passe incorrect.</font>
<?php
}
?>
