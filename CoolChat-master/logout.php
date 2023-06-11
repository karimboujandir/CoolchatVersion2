<?php
// Démarre la session
session_start();

// Détruit toutes les données de la session en cours
session_destroy();

// Redirection
header('Location: index.php');

exit;
?>

