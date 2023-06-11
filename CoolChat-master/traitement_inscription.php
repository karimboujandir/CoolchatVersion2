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
    die('Error : ' . $e->getMessage());
}

// Vérifie si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // On récupère les valeurs
    $pseudo = $_POST['pseudo'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Hachage du mot de passe
   // $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    // Définition du statut
    $statut = 'actif';

    // On prépare la requête SQL pour insérer les données dans la base de données
    $sql = "INSERT INTO Inscrit (pseudo, password, email) 
            VALUES (:pseudo, :password, :email)";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':pseudo', $pseudo);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':email', $email);

    // On exécute la requête SQL
    $stmt->execute();

    // Redirection
    header('Location: accueil.php');
    exit;
}
