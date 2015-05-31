# RES: Web Infrastructure Lab #

## Introduction ##
L'objectif de ce laboratoire est d'apprendre à mettre en place une infrastructure Web en tenant compte du rôle de plusieurs composants (serveurs HTTP, reverse proxy, load balancer).

Pour ça nous avons utilisé plusieurs logiciels (VirtualBox, Vagrant et Docker) afin de créer un environnement virtualisé.

## Infrastructure ##
L'objectif de ce laboratoire est de mettre en place l'infrastructure suivante:

![](http://i.imgur.com/AzaGAq1l.png)

Un utilisateur se connecte via le navigateur Web, qui envoie une requête au reverse proxy qui la fait suivre au front-end. Le front-end retourne ensuite la page HTML avec un script JavaScript qui communique via des requêtes avec le back-end.

## Développement ##
### Back-end ###
La back-end renvoie un numéro aléatoire (à l'aide d'un script JavaScript) lorsqu'il reçoit une requête en utilisant Json. 

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

Tout d'abord, on se base sur l'image "node" disponnible sur le Docker Hub.
Ensuite, le contenu du dossier "files" est copié dans "/opt/res/" de l'image qui sera lancée dans le "container".
Pour finir, le script run.sh sera exécuté au lancement du "container" qui va s'occuper de lancer le fichier JavaScript.

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

Le serveur écoute le port 80 et quand il reçoit une requête de la part de l'utilisateur il renvoie un chiffre aléatoire en réponse (simulation d'un lancement de dé).

#### run.sh ####
>     node /opt/res/app.js -DFOREGROUND

Le script demande à node d'exécuter le script JavaScript app.js.

#### Marche à suivre ####

#### Tests ####

### Front-end ###
Le front-end renvoie une page HTML lorsqu'il reçoit une requête. Cette page HTML contient un script JavaScript qui va communiquer avec le back-end pour le lancement du dé.

#### Structure des fichiers ####
Dossier back-end:
![](http://i.imgur.com/P5mDg6G.png)

Contenu du dossier files:
![](http://i.imgur.com/9OU1hGX.png)

#### Dockerfile ####
>     FROM tutum/apache-php
>     COPY files /app
>     CMD /app/run.sh

Tout d'abord, on va se baser sur l'image apache-php de tutum pour le serveur Apache affin d'afficher des pages avec le module PHP.
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
Dossier loadbalancer:
![](http://i.imgur.com/4ozUiDD.png)

Dossier www:
![](http://i.imgur.com/hGNXNqu.png)

#### Dockerfile ####
>     FROM ubuntu:latest
>      
>     MAINTAINER Dan Pupius <dan@pupi.us>
>      
>     RUN apt-get update
>     RUN apt-get -y upgrade
>      
>     # Install apache, PHP, and supplimentary programs. curl and lynx-cur are for debugging the container.
>     RUN DEBIAN_FRONTEND=noninteractive apt-get -y install apache2 libapache2-mod-php5 php5-mysql php5-gd php-pear php-apc php5-curl curl lynx-cur
>      
>     # Enable apache mods.
>     RUN a2enmod php5
>     RUN a2enmod rewrite
>     RUN a2enmod proxy proxy_balancer proxy_http lbmethod_byrequests lbmethod_bytraffic lbmethod_bybusyness lbmethod_heartbeat
>      
>     # Update the PHP.ini file, enable <? ?tags and quieten logging.
>     RUN sed -i "s/short_open_tag = Off/short_open_tag = On/" /etc/php5/apache2/php.ini
>     RUN sed -i "s/error_reporting = .*$/error_reporting = E_ERROR | E_WARNING | E_PARSE/" /etc/php5/apache2/php.ini
>      
>     # Manually set up the apache environment variables
>     ENV APACHE_RUN_USER www-data
>     ENV APACHE_RUN_GROUP www-data
>     ENV APACHE_LOG_DIR /var/log/apache2
>     ENV APACHE_LOCK_DIR /var/lock/apache2
>     ENV APACHE_PID_FILE /var/run/apache2.pid
>      
>     EXPOSE 80
>      
>     # Copy site into place.
>     ADD www /var/www/site
>      
>     # Update the default apache site with the config we created.
>     ADD apache-config.conf /etc/apache2/sites-enabled/000-default.conf
>      
>     # By default, simply start apache.
>     CMD /usr/sbin/apache2ctl -D FOREGROUND
>     #COPY run.sh /var/www/
>     #CMD /var/www/run.sh

Dans cette section, nous installons le serveur Apache ainsi que l'activation des modules nécessaires au fonctionnement du reverse proxy et du load balancer dans le "container".

#### apache-config.conf ####
>     <VirtualHost *:80>
>     ServerAdmin me@mydomain.com
>     DocumentRoot /var/www/site
>     
>     <Directory /var/www/site/>
>     Options Indexes FollowSymLinks MultiViews
>     AllowOverride All
>     Order deny,allow
>     Allow from all
>     </Directory>
>     
>     ErrorLog ${APACHE_LOG_DIR}/error.log
>     CustomLog ${APACHE_LOG_DIR}/access.log combined
>     
>     <Proxy balancer://frontend>
>             
>     # WebHead1
>     BalancerMember http://172.17.0.30
>     
>     # Load Balancer Settings
>     # We will be configuring a simple Round
>     # Robin style load balancer.  This means
>     # that all webheads take an equal share of
>     # of the load.
>     ProxySet lbmethod=bybusyness
>     ProxySet stickysession=ROUTEID
>     
>     </Proxy>
>     
>     <Proxy balancer://backend>
>             # Enlever les balancerMember et les mettre depuis l'UDP discovery
>             BalancerMember http://172.17.0.18
>             
>             ProxySet lbmethod=byrequests
>     </Proxy>
>     
>     
>     # Point of Balance
>     # This setting will allow to explicitly name the
>     # the location in the site that we want to be
>     # balanced, in this example we will balance "/"
>     # or everything in the site.
>     ProxyPass /balancer-manager !
>     ProxyPass /front balancer://frontend
>     ProxyPass /back balancer://backend
>     
>     </VirtualHost>

Ce fichier est utilisé pour configurer le serveur Apache pour le Loadbalancer ainsi que le reverse proxy. Pour le moment, les adresses des serveurs de front-end et de back-end sont mises en dur (elles doivent ensuite être mises correctement grâce au UDP discovery).

#### readme.txt ####
Marche à suivre pour lancer le container.

#### run.sh ####
>     apachectl start

Lancer le serveur Apache.

#### index.php ####
>     <?php echo "<p>Apache - PHP - Load balancer?</p>"; ?>

#### udpDiscovery.php ####

Première version de l'UDP discovery (pas fonctionnelle), plus d'informations dans la partie de l'UDP discovery.

>     <?php
>     
>     const RESPONSE = 'Je suis là';
>     
>     if(!($socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP))) {
>         die('[ERREUR] : impossible de créer le socket');
>     }
>     socket_set_option($socket, SOL_SOCKET, MCAST_JOIN_GROUP, array('group' ='225.1.1.1', 'interface' ='eth0'));
>     
>     $binded = socket_bind($socket, '0.0.0.0', 5000);
>     
>     $buff = 'Qui est-là ?';
>     
>     socket_send($socket, $buff, strlen($buff), MSG_EOF);
>     
>     while(true) {
>         $buffRet = '';
>         if(socket_recv($socket, $buffRet, strlen(RESPONSE), MSG_EOF) === FALSE) {
>             die('[ERREUR] : impossible de recevoir des data');
>         }
>         
>         echo '<script type="text/javascript">console.log([DEBUG] : ' .$buffRet. ');</script>';
>     }
>     
>     socket_close($socket);

#### Marche à suivre ####
**Commandes à lancer :** 
>     docker build -t mysite .
>     docker run -i -t -p 8080:80 mysite /bin/bash

**Une fois dans la console:**
>     apachectl start

**Remarques:**

*Normalement on doit pouvoir accéder au site depuis 192.168.42.42:8080 et au load balancer avec 192.168.42.42:8080/test.*

*Possibilité de modifier le fichier de configuration pour changer la méthode de load balancing (lbmethod_byrequests, ...).*

#### Tests ####

### UDP Discovery ###
L'implémentation de l'UDP discovery peut se faire de plusieurs manières différentes.

Pour son fonctionnement, on peut imaginer les scénarios suivants:

1. Chaque *x* temps, le serveur envoie une requête en multicast avec le message:
	- *Qui est-là ?*
2. Chaque *x* temps, les noeuds s'annoncent en multicast avec le message:
	- *Je suis là !*
3. Le noeud s'annonce une première fois et chaque *x* temps, le serveur envoie en unicast pour chaque noeud le message:
	- *Est-ce que tu es toujours là ?*

Pour son implémentation, on peut imaginer les scénarios suivants:

1. Le serveur UDP discovery est implémenter sur le load balancer:	
	1. Il faut modifier le fichier de configuration du serveur Apache
	2. Il faut redémarer le serveur Apache
	3. **/!\ Il faut démarrer le load balancer avec le serveur UDP discovery et pas le serveur Apache**
2. Le serveur UDP discovery et mis sur un conteneur seul:
	1. Il faut modifier le fichier de configuration du serveur Apache
	2. Il faut *re-build* l'image Docker du load balancer
	3. **/!\ Il faut démarrer le conteneur du serveur UDP discovery avec une option de gestion de l'infrastructure Docker (option `--privileged` et lui passer le fichier `docker.sock`)**

Pour la partie développement à proprement parler, on peut utiliser l'[API de Docker](https://docs.docker.com/reference/api/docker_remote_api/).

## Conclusion ##
Pendant ce laboratoire nous avons fait face à une situation concrète et complexe de gestion d'une infrastucture Web. Ceci nous a permis de mieux assimiler la théorie vue en classe.

Nous avons rencontré quelques problèmes qui nous ont empéchés d'assembler les différentes parties afin d'avoir un résultat final complet et fonctionnel.
