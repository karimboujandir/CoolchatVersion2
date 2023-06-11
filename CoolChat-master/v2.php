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
    // redirection vers la page d'accueil
    header('Location: accueil.php');
    exit;
}

// récupérer l'identifiant de l'inscrit à modifier
$email = $_POST['email'];

// vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // récupérer les données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $pseudo = $_POST['pseudo'];
    $password = $_POST['password'];
    $commentaire = $_POST['commentaire'];
    $statut = $_POST['statut'];

    // préparer la requête SQL pour mettre à jour les données de l'inscrit
    $sql = "UPDATE Inscrit SET nom = :nom, prenom = :prenom, pseudo = :pseudo, password = :password, commentaire = :commentaire, statut = :statut WHERE email = :email";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':prenom', $prenom);
    $stmt->bindParam(':pseudo', $pseudo);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':commentaire', $commentaire);
    $stmt->bindParam(':statut', $statut);
    $stmt->bindParam(':email', $email);

    // exécuter la requête SQL
    $stmt->execute();

    // rediriger l'utilisateur vers la liste des inscrits
    header('Location: list_inscrits.php');
    exit;
}

// sélectionner l'inscrit à modifier
$sql = "SELECT * FROM Inscrit WHERE email = :email";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':email', $email);
$stmt->execute();
$inscrit = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un inscrit</title>
    <?php include_once('startsession.php'); ?>
    <link rel="stylesheet" href="./css/globale.css">
    <link rel="stylesheet" href="./css/compte.css">

</head>

<body>
    <?php include_once('navbar.php'); ?>


        <div class="conteneur">
            <div class="container" id="container">
                <div class="form-container sign-up-container">
                    <form action="compte.php?id=<?php echo $email; ?>" method="post">
                        <h2>Modifier</h2>
                        <label for="nom">Nom :</label>
                        <input type="text" name="nom" id="nom" value="<?php echo isset($_POST['nom']) ? $_POST['nom'] : (isset($oldUser['nom']) ? $oldUser['nom'] : ''); ?>">

                        <label for="prenom">Prénom :</label>
                        <input type="text" name="prenom" id="prenom" value="<?php echo isset($_POST['prenom']) ? $_POST['prenom'] : (isset($oldUser['prenom']) ? $oldUser['prenom'] : ''); ?>">

                        <label for="pseudo">Pseudo :</label>
                        <input type="text" name="pseudo" id="pseudo" value="<?php echo isset($_POST['pseudo']) ? $_POST['pseudo'] : (isset($oldUser['pseudo']) ? $oldUser['pseudo'] : ''); ?>" required>

                        <label for="password">Mot de passe :</label>
                        <input type="password" name="password" id="password" value="<?php echo isset($_POST['password']) ? $_POST['password'] : (isset($oldUser['password']) ? $oldUser['password'] : ''); ?>" required>

                        <label for="commentaire">À propos de moi :</label>
                        <textarea name="commentaire" id="commentaire"><?php echo isset($_POST['commentaire']) ? $_POST['commentaire'] : (isset($oldUser['commentaire']) ? $oldUser['commentaire'] : ''); ?></textarea>

                        <label for="statut">Statut :</label>
                        <select name="statut" id="statut">
                            <option value="Actif" <?php if (isset($_POST['statut']) && $_POST['statut'] == 'Actif') echo 'selected'; ?>>
                                <?php echo isset($oldUser['statut']) ? $oldUser['statut'] : 'connecté'; ?></option>
                            <option value="Inactif" <?php if (isset($_POST['statut']) && $_POST['statut'] == 'Inactif') echo 'selected'; ?>>
                                <?php echo isset($oldUser['statut']) ? $oldUser['statut'] : 'deconnecté'; ?></option>
                        </select>

                        <button class="modifier" type="submit" name="modification">Modifier</button>
                    </form>
                </div>

                <div class="form-container sign-in-container">
                    <form>
                        <h2>Profil</h2>
                        <h3>Pseudo:</h3>
                        <p class="info"><?php echo $_SESSION['user']['pseudo']; ?></p>
                        <h3>Nom:</h3>
                        <p class="info"><?php echo $_SESSION['user']['nom']; ?></p>
                        <h3>Prénom:</h3>
                        <p class="info"><?php echo $_SESSION['user']['prenom']; ?></p>
                        <h3>A propos de moi:</h3>
                        <p class="info"><?php echo $_SESSION['user']['commentaire']; ?></p>
                        <h3><?php echo $_SESSION['user']['statut']; ?></h3>
                    </form>
                </div>
                <div class="overlay-container">
                    <div class="overlay">
                        <div class="overlay-panel overlay-left">
                            <h2 class="blanc">Mes informations</h2>

                            <button class="ghost" id="signIn">voir profil</button>
                        </div>
                        <div class="overlay-panel overlay-right">
                            <h2 class="blanc"><?php echo $_SESSION['user']['pseudo']; ?></h2>
                            <p>Un peu de fraicheur ? modifier votre profil !</p>
                            <button class="ghost" id="signUp">Modifier</button>
                            <button><a class="blanc" href="supprimer.php?id=<?php echo $_SESSION['user']['email']; ?>" onclick="return confirm('Voulez-vous vraiment supprimer votre compte ?')">Supprimer
                                    mon compte</a></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
    <script src="./javascript/navbar.js"></script>
    <script src="connexion.js"></script>
</body>

</html>