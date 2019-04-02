<?php
/**
 * Déclaration des variables de connexion
 *
 * - $host_name : nom d'hôte (ici localhost pour un serveur local)
 * - $database : nom de la base de données (DB)
 * - $user_name : nom d'utilisateur de la DB
 * - $password : mot de passe de la DB
 */
$host_name = 'localhost';
$database = 'memory_game';
$user_name = 'root';
$password = '';

/**
 * Connexion à la DB
 */
$dbh = null;
try {
    $dbh = new PDO("mysql:host=$host_name; dbname=$database;", $user_name, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
} catch (PDOException $e) {
    echo "Erreur!: " . $e->getMessage() . "<br/>";
    die();
}
