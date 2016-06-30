<?php
/* Esta clase se encarga de comprobar si game_score.php funciona correctamente*/
require_once('./curl.php');
//Parametros
//$token = '650858fa0af4b404a5a2351a3fbb024a'; //pedro_ws
$token = 'af0b8ee387f2631085e86541d912dbc5'; //juan_ws
//Actividades de juego creadas en Moodle en el curso aplicaciones web (aw)
// 1 el alcalde de Zalamea en curso aw
// 2 la dama boba en curso aw
$gameid = 1;
// calificacion del jugador.
$note = 10;
//llamamos a la 
$url_token='http://localhost/moodle/local/wsgames/game_score.php'.'?token='. $token .'&gameid='. $gameid .'&score='.$note ;
$curl = new curl;
//obtenemos la respuesta
$resp= $curl->get($url_token);
print_r($resp);
