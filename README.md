# Memory-Game

Voilà les fichiers du Memory Game que j'ai développé.
J'ai utilisé HTML/CSS (avec Sass et le framework Compass)/jQuery et PHP avec une base de données SQL.

Il y a 2 builds :
* les fichiers de dev commentés (dev-build)
* les fichiers minifiés pour la prod (live-build)

Le live-build est déployé sur mon serveur OVH : http://www.web-developer.fr/

### Fonctionnement du jeu
* le joueur accède au jeu et retrouve les 5 meilleurs temps effectués
* il doit saisir un pseudo pour pouvoir lancer le jeu
* il a ensuite 2 minutes pour retrouver les 28 paires
* si il retrouve toutes les paires, un message s'affiche avec son temps et les infos sont saisies en DB (via un appel en AJAX). Il peut alors rejouer
* si il ne retrouve pas toutes les paires en 2 minutes, un message s'affiche lui indiquant qu'il a perduet qu'il peut rejouer

### Consignes pour le déploiement
* éditer le fichier "includes/cn.php" et indiquer les infos de connexion à la DB
* récupérer le fichier "DB/participants.sql" (qui contient la table de stockage des participations) et l'importer dans la DB
