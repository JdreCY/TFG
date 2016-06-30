<?php 
/* Esta clase se encarga de comprobar si el usuario existe en la base de datos de Moodle. 
Comprueba usuario-contraseña y si el usuario tiene permisos para crear tokens.
Tambien hace una comprobacion del identificador del videojuego
*/

require_once('/xampp/htdocs/moodle/config.php');
require_once($CFG->libdir . '/filelib.php');
global $DB;
//Obtenemos los datos que nos envia el videojuego
$username = required_param('username', PARAM_USERNAME);
$password = required_param('password', PARAM_RAW);
$idgame   = required_param('idgame',PARAM_INT); 
//necesario para hacer la llamada php
$curl = new curl;
//el nombre corto del servicio al que el cliente quiere conectarse
$serviceshortname = 'wsgames';
//Con esta funcion obtenemos el token, clave unica ligada a un usuario Moodle
$url_token='http://localhost/moodle/login/token.php'.'?username='. $username .'&password='. $password .'&service='. $serviceshortname;
$usertoken = new stdClass;
//obtenemos la respuesta
$resp= $curl->get($url_token);
//tratamos la respuesta. Usamos explode para dividirla. token.php devuelve un string, ejemplo token:1235566j
$respuesta = explode(":", $resp);
//comprobamos que nos devuelve el token
$etiqueta = substr($respuesta[0], 2, 5);
if(strnatcasecmp ( $etiqueta , "token" )== 0)
{
	//Tenemos el token, es decir es un usuario de moodle. Ahora comprobamos que idgame esta asociado a una actividad game
	//En la tabla game estan las instancias creadas del módulo para el profesor
	$game_course = $DB->get_record('game', array('idgame'=>$idgame ));
	if(empty($game_course)){
		echo "Fail:idgame";
	}else{
		//enviamos el token al cliente
		$token = substr($respuesta[1], 1, 32);
		echo "Success:".$token ;
	}

}
else{
	echo "Fail:data";
}



