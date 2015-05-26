Commandes à lancer : 
	docker build -t mysite .
	docker run -i -t -p 8080:80 mysite /bin/bash
	
	# une fois dans la console
	apachectl start
	
	# normalement on doit pouvoir accéder au site depuis 192.168.42.42:8080
	# et au load balancer avec 192.168.42.42:8080/test
	
	# possibilité de modifier le fichier de conf pour changer la méthode de load
	# balancing (lbmethod_byrequests ...)
