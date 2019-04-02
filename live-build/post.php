<?php
session_start();

/**
 * On appelle le fichier de connexion à la DB
 */
include 'includes/cn.php';

/**
 * Récupération du pseudo
 */
if ((isset($_SESSION['pseudo'])) && (!empty($_SESSION['pseudo']))) {
    $pseudo = $_SESSION['pseudo'];
}
/**
 * Récupération du temps
 */
if ((isset($_POST["gametime"])) && (!empty($_POST["gametime"]))) {
    $gametime = $_POST["gametime"];
}

/**
 * Insertion des données (requête préparée)
 */
try {
    $stmt = "INSERT INTO participants (pseudo, game_time, creation_date) VALUES (?,?,?)";
    $dbh->prepare($stmt)->execute([$pseudo, $gametime, date('Y-m-d H:i:s')]);
} catch (PDOException $e) {
    echo "Erreur!: " . $e->getMessage() . "<br/>";
}
/**
 * On ferme la connexion
 */
$stmt = null;
