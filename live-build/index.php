<?php
session_start();
if (isset($_POST['send'])) {
    $_SESSION['pseudo'] = $_POST['pseudo'];
}
include 'includes/cn.php';
$stmt = $dbh->prepare("SELECT * FROM participants ORDER BY game_time ASC LIMIT 5");
$stmt->execute();
?>
<!DOCTYPE html><html lang="fr"><head> <meta charset="UTF-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0"> <meta http-equiv="X-UA-Compatible" content="ie=edge"> <title>O'clock Memory Game</title> <link rel="icon" type="image/png" href="favicon-16x16.png" sizes="16x16"> <link rel="icon" type="image/png" href="favicon-32x32.png" sizes="32x32"> <link href="//fonts.googleapis.com/css?family=Open+Sans:400,700|Roboto:700" rel="stylesheet"> <link rel="stylesheet" type="text/css" href="//stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> <link rel="stylesheet" type="text/css" href="stylesheets/screen.css"></head><body> <header> <div class="logo"></div><h1>The great Memory&nbsp;Game</h1> </header> <main> <div class="overlay start"> <h2>Le but du jeu est de découvrir toutes les paires <strong>en moins de 2&nbsp;minutes&nbsp;!</strong></h2> <table id="scores"> <caption>Hall of Fame</caption> <tr> <th>Pseudo</th> <th>Temps</th> </tr>
  <?php
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      if (empty($row)) {
        $str = <<<EOD
          <tr>
           <td colspan="2">Il n'y a pas encore de participations</td>
          </tr>
EOD;
       echo $str;
      }
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
  $stmt = null;
  ?>
</table> <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="dataForm" id="dataForm" onsubmit="return checkForm()"> <fieldset> <label for="pseudo">Pour jouer, saisis ton pseudo et clique sur START</label> <input type="text" id="pseudo" name="pseudo" placeholder="Pseudo"><br/> <input type="submit" value="START" id="send" name="send"> </fieldset> </form> </div><div class="overlay lost" style="display:none;"> <h2>Le temps est écoulé &#128545;</h2> <p>Retente ta chance&nbsp;!<br/> <button onclick="window.location.reload();">Rejouer</button></p></div><div class="overlay win" style="display:none;"></div><div id="tip-box"><p>Clique sur 2 cartes</p></div><ul class="game" id="cardGame"> <li class="cardArea"><div></div></li><li class="cardArea"><div></div></li><li class="cardArea"><div></div></li><li class="cardArea"><div></div></li><li class="cardArea"><div></div></li><li class="cardArea"><div></div></li><li class="cardArea"><div></div></li><li class="cardArea"><div></div></li><li class="cardArea"><div></div></li><li class="cardArea"><div></div></li><li class="cardArea"><div></div></li><li class="cardArea"><div></div></li><li class="cardArea"><div></div></li><li class="cardArea"><div></div></li><li class="cardArea"><div></div></li><li class="cardArea"><div></div></li><li class="cardArea"><div></div></li><li class="cardArea"><div></div></li><li class="cardArea"><div></div></li><li class="cardArea"><div></div></li><li class="cardArea"><div></div></li><li class="cardArea"><div></div></li><li class="cardArea"><div></div></li><li class="cardArea"><div></div></li><li class="cardArea"><div></div></li><li class="cardArea"><div></div></li><li class="cardArea"><div></div></li><li class="cardArea"><div></div></li></ul> <input type="hidden" id="choice1" value="00" class="hide"> <div class="progressArea"> <label for="progress">Fais attention au temps...</label> <div id="progress"></div></div></main> <footer> Memory Game by Fabrice Duchénois - &copy; O'clock 2019 </footer> <script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> <script src="//stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script> <script src="javascripts/jquery.progressBarTimer.min.js"></script> <script src="javascripts/scripts.js"></script> <?php if (isset($_SESSION['pseudo'])){echo '<script>startGame();</script>';}?></body></html>
