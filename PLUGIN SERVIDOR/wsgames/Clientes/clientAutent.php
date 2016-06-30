<?php
/* Esta clase sirve para comprobar game_login.php.
Es necesario pasarle usuario, contraseña y un identificador de videojuego
Esta clase ha sido usada para hacer las pruebas con el servicio web wsgames.
*/
//parametro necesarios
$USERNAME = 'juan_ws';
$PASSWORD= 'Student_11';
$idGame = '1';
//llamada a la clase game_login
$url_token='http://localhost/moodle/local/wsgames/game_login.php'.'?username='. $USERNAME .'&password='. $PASSWORD .'&idgame='.$idGame ;
require_once('./curl.php');
$curl = new curl;
//obtenemos la respuesta
$resp= $curl->get($url_token);
//comprobamos si nos devuelve el token o no
if($resp==false)
	print_r("fallo");
else{	
	//ya tengo el token
		print_r("resultado".$resp);
}
 

