Activer les mods suivant :

	proxy
	proxy_balancer
	proxy_http
	
	# En fonction des méthodes de LB utilisées
	lbmethod_byrequests
	lbmethod_bytraffic
	lbmethod_bybusyness
	
	
	-> a2enmod proxy proxy_balancer proxy_http lbmethod_byrequests lbmethod_bytraffic lbmethod_bybusyness lbmethod_heartbeat
