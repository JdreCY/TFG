<?php

// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * External Web Service Template
 *
 * @package    localwstemplate
 * @copyright  2011 Moodle Pty Ltd (http://moodle.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once($CFG->libdir . "/externallib.php");

class local_wsgames_external extends external_api {

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function game_score_parameters() {
        return new external_function_parameters(
                array('gameid' => new external_value(PARAM_INT, 'id of game', VALUE_DEFAULT, 0),
				'score' => new external_value(PARAM_INT, 'score of student', VALUE_DEFAULT, 0))
        );
    }

    /**
     * Returns welcome message
     * @return string welcome message
     */
    public static function game_score($gameid, $score) {
        global $USER,$DB;

        //Parameter validation
        //REQUIRED

        $params = self::validate_parameters(self::game_score_parameters(),
                array('gameid' => $gameid,
				'score' => $score));	
		//Comprobamos que el id game pertenece a un curso y obtenemos el id del curso
		$game_course = $DB->get_record('game', array('idgame'=>$params['gameid']));

		if(empty($game_course)){
			return "Fail:noGame";
		}else{
			$course_id = $game_course->course;	
			//Comprobamos que el usuario esta matriculado en el curso
			$context = get_context_instance(CONTEXT_COURSE, $course_id, MUST_EXIST);
			$enrolled = is_enrolled($context,$USER->id, '', true); 
			if($enrolled){
				//comprobamos si existe calificacion del usuario para ese juego
				//$game = $DB->get_record('scores', array('id_game'=>$params['gameid'],'id_user'=>$USER->id));
				$game =  $DB->get_record_sql('SELECT * FROM {score} WHERE idgame = ? AND iduser = ?', 
                       array($params['gameid'], $USER->id));
				if(empty($game)){
					//añadir nuevo registro
					$dataobject = new stdClass();
					$dataobject->idgame = $gameid ;
					$dataobject->iduser = $USER->id;
					$dataobject->course = $course_id ;
					$dataobject->score = $score;	
					$insertar = $DB->insert_record('score', $dataobject,false);
					if($insertar){
						return "Success:insert";
					}else{
						return "Fail:insert";
					}
				}
				else{
					//comprobar si la calificacion actual es mayor que la nueva calificacion
					if($game->score>= $score){
						//no hacemos nada
						return "Success:noUpdate";
					}else{
						$dataobject = new stdClass();
						$dataobject->id = $game->id ;
						$dataobject->idgame = $game->idgame ;
						$dataobject->iduser = $game->iduser ;
						$dataobject->course = $game->course ;
						$dataobject->score = $score;
						//actualizamos registro
						$actualizacion = $DB->update_record('score', $dataobject,false);
						//return $actualizacion ;
						if($actualizacion ){
							return "Success:update";	
						}else{
							return "Fail:update";
						}
					}
				}
			}else
			{
				return "Fail:noCourse";
			}
		}
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function game_score_returns() {
        return new external_value(PARAM_TEXT, 'Success or fail');
    }



}
