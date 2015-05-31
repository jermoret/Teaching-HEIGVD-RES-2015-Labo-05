# RES: Web Infrastructure Lab #

## Introduction ##
L'objectif de ce laboratoire est d'apprendre à mettre en place une infrastructure Web en tenant compte du rôle de plusieurs composants (serveurs http, reverse proxy, équilibreurs de charge).

Pour ça nous avons utilisé plusieurs logiciels (VirtualBox, Vagrant et Docker) afin de créer un environnement virtualisé.

## Infrastructure ##
L'objectif de ce laboratoire est de mettre en place l'infrastructure suivante:

![](http://i.imgur.com/AzaGAq1l.png)

## Développement ##
### Back-end ###
Nous avons décidé dans un premier temps d’écrire un script en JavaScript qui lorsqu’il reçoit une requête http sur le port 80 avec comme URI un ‘/’  , il répond avec un Json qui contient le nom d’un étudiant tiré au hasard parmi une liste d’étudiant.

#### Structure des fichiers ####
Dossier back-end:
![](http://i.imgur.com/P5mDg6G.png)

Contenu du dossier files:
![](http://i.imgur.com/j6qdQyl.png)

#### Dockerfile ####
Dockerfile réalisé pour la partie back-end:
>     FROM node
>     MAINTAINER Jerome Moret
>     COPY files /opt/res/
>     RUN npm install express
>     CMD /opt/res/run.sh

Tout d'abord, nous chargeons "node" pour pouvoir lancer un script JavaScript.
Ensuite, le contenu du dossier "files" est copié dans le dossier "/opt/res/" de l'image qui sera lancé dans le "container".
Pour finir, le fichier run.sh sera exécuté au lancement du "container" qui s'occupe de lancer le fichier JavaScript.

#### app.js ####
>     var express = require('express');
>     var app = express();
>     var dice = new Object();
>     
>     app.get('/', function (req, res) {
>         res.setHeader('Content-Type', 'application/json');
>         dice.value = Math.floor((Math.random() * 6) + 1);
>         res.send( JSON.stringify(dice) );
>     });
>     
>     var server = app.listen(80, function () {
>     
>       var host = server.address().address;
>       var port = server.address().port;
>     
>     });

Le serveur écoute le port 80 et quand il reçoit une requête de la part de l'utilisateur il renvoie un chiffre aléatoire en réponse (lancement du dès).

#### run.js ####
>     node /opt/res/app.js -DFOREGROUND

Le script lance simplement le script JavaScript.

#### Marche à suivre ####

#### Tests ####

### Front-end ###
#### Structure des fichiers ####
Dossier back-end:
![](http://i.imgur.com/P5mDg6G.png)

Contenu du dossier files:
![](http://i.imgur.com/9OU1hGX.png)

#### Dockerfile ####
>     FROM tutum/apache-php
>     COPY files /app
>     CMD /app/run.sh

Tout d'abord, nous chargeons le serveur Apache pour afficher des pages avec le module PHP.
Ensuite, le contenu du dossier "files" est copié dans l'image à l'emplacement "/app".
Pour finir, le script run.sh est exécuté au démarrage du "container".

#### index.php ####
>     <html>
>         <head>
>             <title>Jeu de dé</title>    
>             <script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
>         </head>
>         <body>
>             <div>
>                 <h1>Jeu de dé</h1>
>                 <p>Cliquez sur le bouton pour lancer le dé.</p>
>                 <p><button type="button" name="dice" onClick="getJson();">Lancer le dé</button></p>
>     
>                 <script>
>                 function getJson() {    
>                     $.getJSON("/back", function(result) {
>                         $.each(result, function(name, value) {    
>                             alert(value);
>                             $("#result").html("Vous avez tiré : ");
>                             $("#result").append(value);
>                         });
>                         
>                         $("#result").show();
>                     });
>                 }
>                 </script>
>                 <div id="result" class="result"></div>
>             </div>
>         </body>
>     </html>

Quand l'utilisateur accède au front-end depuis un navigateur web, une requête sera renvoyé au back-end qui génère le nombre aléatoire et le renvoie au front-end qui l'affichera et cela chaque fois que l'utilisateur cliquera sur le bouton "Lancer le dé".

#### jquey-2.1.4.min.js ####
Bibliothèque JavaScript utilisé pour les requêtes Ajax.

#### run.sh ####
>     apachectl -DFOREGROUND

Démarrage du serveur Apache.

#### Marche à suivre ####

#### Tests ####

### Load Balancer ###

#### Structure des fichiers ####

#### Marche à suivre ####

#### Tests ####

