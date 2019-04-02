<?php
/**
 * On démarre une session
 */
session_start();
/**
 * Pour détruire la session. décommenter si nécessaire
 */
//session_destroy();

/**
 * On ajoute le pseudo à la session
 */
if (isset($_POST['send'])) {
    $_SESSION['pseudo'] = $_POST['pseudo'];
}

/**
 * On appelle le fichier de connexion à la DB
 */
include 'includes/cn.php';

/**
 * Récupération des données en DB (requête préparée)
 */
$stmt = $dbh->prepare("SELECT * FROM participants ORDER BY game_time ASC LIMIT 5");
$stmt->execute();
?>

<!-- Début de la page HTML -->
<!DOCTYPE html>
<html lang="fr">

<head>
  <!-- Encodage de la page en UTF-8 -->
  <meta charset="UTF-8">
  <!-- Déclaration du comportement du viewport pour les mobiles -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Désactivation du mode de compatibilité sur IE -->
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <!-- Titre de la page -->
  <title>O'clock Memory Game</title>
  <!-- Appel des favicons -->
  <link rel="icon" type="image/png" href="favicon-16x16.png" sizes="16x16">
  <link rel="icon" type="image/png" href="favicon-32x32.png" sizes="32x32">
  <!-- Appel des Google fonts -->
  <link href="//fonts.googleapis.com/css?family=Open+Sans:400,700|Roboto:700" rel="stylesheet">
  <!-- Appel des fichiers CSS nécessaires -->
  <link rel="stylesheet" type="text/css" href="//stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="stylesheets/screen.css">
</head>

<body>
  <!-- Header avec logo et titre -->
  <header>
    <div class="logo"></div>
    <h1>The great Memory&nbsp;Game</h1>
  </header>

  <!-- Corps de la page -->
  <main>
    <!-- Overlayer de début -->
    <div class="overlay start">
      <h2>Le but du jeu est de découvrir toutes les paires <strong>en moins de 2&nbsp;minutes&nbsp;!</strong></h2>

      <!-- Tableau des 5 meilleurs scores -->
      <table id="scores">
        <caption>Hall of Fame</caption>
        <tr>
          <th>Pseudo</th>
          <th>Temps</th>
        </tr>

       <?php
       /**
        * Boucle pour afficher les données en DB
        */
       while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
         /**
          * Le tableau est vide -> pas de données en DB
          */
           if (empty($row)) {
             /**
              * Syntaxe Heredoc pour ecrire sur plusieurs lignes
              */
             $str = <<<EOD
               <tr>
                <td colspan="2">Il n'y a pas encore de participations</td>
               </tr>
EOD;
            echo $str;
           }
           /**
            * Le tableau n'est pas vide -> on affiche les données
            */
           else {
               $pseudo = htmlentities($row['pseudo']);
               $gametime = substr(htmlentities($row['game_time']), 3);
               $str = <<<EOD
                 <tr>
                  <td>$pseudo</td>
                  <td>$gametime</td>
                 </tr>
EOD;
               echo $str;
           }
       }

       /**
        * On ferme la connexion
        */
       $stmt = null;
       ?>
      </table>

      <!-- Formulaire du pseudo -->
      <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="dataForm" id="dataForm" onsubmit="return checkForm()">
        <fieldset>
          <label for="pseudo">Pour jouer, saisis ton pseudo et clique sur START</label>
          <input type="text" id="pseudo" name="pseudo" placeholder="Pseudo"><br />
          <input type="submit" value="START" id="send" name="send">
        </fieldset>
      </form>
    </div>

    <!-- Overlayer affiché quand le temps est dépassé -->
    <div class="overlay lost" style="display:none;">
      <h2>Le temps est écoulé &#128545;</h2>
      <p>Retente ta chance&nbsp;!<br />
      <button onclick="window.location.reload();">Rejouer</button></p>
    </div>

    <!-- Overlayer affiché quand le joueur a gagné -->
    <div class="overlay win" style="display:none;"></div>

    <!-- Div pour affficher différents messages -->
    <div id="tip-box"><p>Clique sur 2 cartes</p></div>

    <!-- Disposition des cartes du jeu -->
    <ul class="game" id="cardGame">
      <li class="cardArea"><div></div></li>
      <li class="cardArea"><div></div></li>
      <li class="cardArea"><div></div></li>
      <li class="cardArea"><div></div></li>
      <li class="cardArea"><div></div></li>
      <li class="cardArea"><div></div></li>
      <li class="cardArea"><div></div></li>
      <li class="cardArea"><div></div></li>
      <li class="cardArea"><div></div></li>
      <li class="cardArea"><div></div></li>
      <li class="cardArea"><div></div></li>
      <li class="cardArea"><div></div></li>
      <li class="cardArea"><div></div></li>
      <li class="cardArea"><div></div></li>
      <li class="cardArea"><div></div></li>
      <li class="cardArea"><div></div></li>
      <li class="cardArea"><div></div></li>
      <li class="cardArea"><div></div></li>
      <li class="cardArea"><div></div></li>
      <li class="cardArea"><div></div></li>
      <li class="cardArea"><div></div></li>
      <li class="cardArea"><div></div></li>
      <li class="cardArea"><div></div></li>
      <li class="cardArea"><div></div></li>
      <li class="cardArea"><div></div></li>
      <li class="cardArea"><div></div></li>
      <li class="cardArea"><div></div></li>
      <li class="cardArea"><div></div></li>
    </ul>
    <!-- Champ caché pour attribuer des valeurs lors du jeu -->
    <input type="hidden" id="choice1" value="00" class="hide">

    <!-- Barre de temps -->
    <div class="progressArea">
      <label for="progress">Fais attention au temps...</label>
      <div id="progress"></div>
    </div>
  </main>

  <footer>
    Memory Game by Fabrice Duchénois - &copy; O'clock 2019
  </footer>
  <!-- Appel des fichiers JS nécessaires -->
  <script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="//stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
  <script src="javascripts/jquery.progressBarTimer.min.js"></script>
  <script src="javascripts/scripts.js"></script>
  <?php
    /**
     * Si il y a un pseudo en session, on lance le jeu
     */
    if (isset($_SESSION['pseudo'])) {
        echo '<script>startGame();</script>';
    }
  ?>
</body>

</html>
