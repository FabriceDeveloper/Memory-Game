// On crée un tableau contenant toutes les cartes
var array = ['01-1', '01-2', '02-1', '02-2', '03-1', '03-2', '04-1', '04-2', '05-1', '05-2', '06-1', '06-2', '07-1', '07-2', '08-1', '08-2', '09-1', '09-2', '10-1', '10-2', '11-1', '11-2', '12-1', '12-2', '13-1', '13-2', '14-1', '14-2'];

// Fonction qui va mélanger aléatoirement les cartes du tableau
function shuffleArray(array) {
  for (var i = array.length - 1; i > 0; i--) {
    var j = Math.floor(Math.random() * (i + 1));
    var temp = array[i];
    array[i] = array[j];
    array[j] = temp;
  }
  return array;
}

function checkForm() {
  // Si le champ pseudo n'est pas rempli, on affiche un message
  if ($('#pseudo').val() == '') {
    $('#pseudo').addClass('error');
    return false;
  }
  // Si le champ pseudo est rempli, on supprime message
  else {
    $('#pseudo').removeClass('error');
    return;
  }
}

// La partie commence quand cette fonction est appelée !
function startGame() {
  // On mélange !
  array = shuffleArray(array);
  // On attribut les classes aux cartes mélangées
  $.each($('.cardArea div'), function(i) {
    $(this).addClass('icons' + array[i]);
  });

  // On masque les overlayers
  $('.overlay').hide();

  // On lance le timer (plugin jquery)
  $('#progress').progressBarTimer({
    autoStart: true,
    smooth: true,
    // 120 secondes pour finir
    timeLimit: 120,
	  // base style
	  baseStyle: 'bg-success',
	  // complete style
	  completeStyle: 'bg-danger',
    // Le temps est écoulé, on désactive le jeu et on affiche un message
    onFinish: function() {
      $('.cardArea').off('click');
      $('.overlay.lost').show();
    }
  });

  // Hauteur de la carte selon desktop/mobile
  if (window.matchMedia('(max-width: 768px)').matches){
    var cardheight = 75;
  }
  else{
    var cardheight = 100;
  }
  // On commence le jeu
  $('.cardArea').on('click', function() {
    // Animation qui affiche la carte
    $(this).children('div').animate({
      height: cardheight
    }, 500);
    // On récupère la classe de la carte cliquée
    var thisClass = $(this).children('div').attr('class');

    // On attribut les chiffres des classes et des valeurs de références  à nos variables
    var thisId = thisClass.substring(5, 7);
    var macthId = $('#choice1').val();
    var choice1 = macthId;
    var choice2 = "00";

    // On teste les cas
    if (choice1 === "00") { // premier clic
      $(this).addClass('checked');
      choice1 = thisId;
      $('#choice1').val(thisId);
    } else if ($(this).hasClass('checked')) { // la carte cliquée est déjà retournée
      $('#tip-box').text("Choisis une seconde carte");
    } else if (choice2 === "00") { // second clic
      choice2 = thisId;
      // on vérifie si les chiffres des classes correpondent
      if (choice1 === choice2) {
        $('#tip-box').html("<p class='textTransition'>Bien joué !</p>");
        $('[class*="' + thisClass.substring(7, 0) + '"]').parent().addClass('matched');
      } else {
        // si ça ne correspond pas, on masque les cartes (avec un petit délai pour mémoriser)
        setTimeout(function() {
          $('#tip-box').html("<p>Essaye encore !</p>");
          $('.cardArea:not(.matched)').children('div').animate({
            height: 0
          }, 500);
        }, 800);
      }
      // On reset les clics
      $('.cardArea').removeClass('checked');
      $('#choice1').val('00');

      // On compte le nombre de artes retournées
      var matchNum = $('.matched').length;
      // Toutes les paires ont été trouvées
      if (matchNum == 28) {
        // On arrête le timer
        $('#progress').progressBarTimer().stop();
        // On convertit les millisecondes en minutes:secondes
        var duration = $('#progress').progressBarTimer().getElapsedTime();
        var seconds = parseInt((duration / 1000) % 60),
          minutes = parseInt((duration / (1000 * 60)) % 60);
        minutes = (minutes < 10) ? "0" + minutes : minutes;
        seconds = (seconds < 10) ? "0" + seconds : seconds;

        // On passe le temps dans une variable
        var totalTime = "00:" + minutes + ":" + seconds;
        var totalTime2Show = minutes + " min " + seconds + " s";

        // On effectue une requête AJAX pour sauvegarder le temps en DB
        $.ajax({
          type: "POST",
          url: "post.php",
          data: 'gametime=' + totalTime,
          success: function() {
            // L'insertion en DB a été effectuée, on affiche le message au joueur avec son temps
            $('.overlay.win').html("<h2>Gagné !&#128513;</h2><p>Ton temps est de <strong>" + totalTime2Show + "</strong><br />Ton score a été ajouté à la liste des participations.</p><p><button onclick='window.location.reload();'>Rejouer</button><p>").show();
          },
          error: function(data) {
            // L'insertion en DB n'a pas été effectuée, on affiche un message d'erreur
            $('.overlay.win').html("<h2>Erreur</h2><p>Une erreur est survenue. <a href='javascript:void(0);' onClick='javascript:window.location.reload();' style='text-decoration:underline;'>Veuillez réessayer</a>.</p>").show();
          }
        });
      }
    }
  });
}
