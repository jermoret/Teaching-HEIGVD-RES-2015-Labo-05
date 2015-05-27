<?php

const RESPONSE = 'Je suis là';

if(!($socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP))) {
	die('[ERREUR] : impossible de créer le socket');
}
socket_set_option($socket, SOL_SOCKET, MCAST_JOIN_GROUP, array('group' => '225.1.1.1', 'interface' => 'eth0'));

$binded = socket_bind($socket, '0.0.0.0', 5000);

$buff = 'Qui est-là ?';

socket_send($socket, $buff, strlen($buff), MSG_EOF);

while(true) {
	$buffRet = '';
	if(socket_recv($socket, $buffRet, strlen(RESPONSE), MSG_EOF) === FALSE) {
		die('[ERREUR] : impossible de recevoir des data');
	}
	
	echo '<script type="text/javascript">console.log([DEBUG] : ' .$buffRet. ');</script>';
}

socket_close($socket);