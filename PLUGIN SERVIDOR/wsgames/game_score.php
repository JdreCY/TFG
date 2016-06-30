<?php
/* Esta clase se encarga de llamar al servicio web que el videojuego quiere usar
Llama a la función qe se encarga de almacenar una calificación en Moodle
*/
require_once('/xampp/htdocs/moodle/config.php');
require_once($CFG->libdir . '/filelib.php');
//Obtenemos los parametros necesarios
$token = required_param('token', PARAM_TEXT);
$gameid = required_param('gameid', PARAM_INT);
$score   = required_param('score',PARAM_INT); 
//Ruta del servicio web.
$domainname = 'http://localhost/moodle';
//Función que el cliente quiere usar
$functionname = 'local_wsgames_game_score';
//LLamamos al protocolo XML-RPC pasandole el token del usuario
$serverurl = $domainname . '/webservice/xmlrpc/server.php'. '?wstoken=' . $token;
$curl = new curl;
$curl->setHeader('Content-type: text/xml');
$post = xmlrpc_encode_request($functionname, array($gameid,$score));
$resp = xmlrpc_decode($curl->post($serverurl, $post));
echo($resp);