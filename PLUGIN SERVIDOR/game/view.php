<?php  // $Id: view.php,v 1.6.2.3 2009/04/17 22:06:25 skodak Exp $

/**
 * This page prints a particular instance of game
 *
 * @author  Your Name <your@email.address>
 * @version $Id: view.php,v 1.6.2.3 2009/04/17 22:06:25 skodak Exp $
 * @package mod/game
 */


require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
global $DB,$USER;

$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$a  = optional_param('a', 0, PARAM_INT);  // game instance ID

if ($id) {
    if (! $cm = get_coursemodule_from_id('game', $id)) {
        error('Course Module ID was incorrect');
    }

    if (! $course = $DB->get_record('course', array('id'=>$cm->course))) {
        error('Course is misconfigured');
    }

    if (! $game = $DB->get_record('game', array('id'=> $cm->instance))) {
        error('Course module is incorrect');
    }

} else if ($a) {
    if (! $game = $DB->get_record('game',array( 'id'=> $a))) {
        error('Course module is incorrect');
    }
    if (! $course = $DB->get_record('course',array( 'id'=> $game->course))) {
        error('Course is misconfigured');
    }
    if (! $cm = get_coursemodule_from_instance('game', $game->id, $course->id)) {
        error('Course Module ID was incorrect');
    }

} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);

$event = \mod_game\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $game);
$event->trigger();
// Print the page header.
$PAGE->set_url('/mod/game/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($game->name));
$PAGE->set_heading(format_string($course->fullname));
/*
 * Other things you may want to set - remove if not needed.
 * $PAGE->set_cacheable(false);
 * $PAGE->set_focuscontrol('some-html-id');
 * $PAGE->add_body_class('game-'.$somevar);
 */
// Output starts here.
echo $OUTPUT->header();
echo $OUTPUT->heading(format_string("GAME ACTIVTY"), 1);
$table = new html_table();
$table = new html_table();
$table->head = array('Name Game');
$table->data = array(
    array(format_string($game->name)),

);
echo html_writer::table($table);
$table = new html_table();
$table = new html_table();
$table->head = array('Description');
$table->data = array(
    array(format_string($game->intro)),

);
echo html_writer::table($table);

$table = new html_table();
$table = new html_table();
$table->head = array('Id Game');
$table->data = array(
    array(format_string($game->idgame)),

);
echo html_writer::table($table);
echo $OUTPUT->heading(format_string("SCORES"), 1);
//$contextmodule = coursecontext ::instance($cm->id);

//$usercontext = context_user::instance($USER->id);
//$roles = get_user_roles($contextmodule, $USER->id);
//if(has_capability('/mod/game/viewmod',$usercontext)){
//obtengo las puntuaciones del juego
$array_scores = $DB->get_records('score', array('idgame' => $game->idgame, 'course' => $course->id));   

if(empty($array_scores)){
	echo $OUTPUT->box_start();
	echo "No hay datos aún";
	echo $OUTPUT->box_end();

}else{

	$table = new html_table();
	$table->head = array('Student', 'Score');
	$users[] = array();
	foreach($array_scores as $student){
		$user=$DB->get_record('user', array('id'=>$student->iduser));
		if($user){
		//	if())
			$users[] = 
				array($user->firstname . ' '.$user->lastname, $student->score);
			
		}
	}
	$table->data = $users;
	echo html_writer::table($table);

//}

}
// Finish the page.
echo $OUTPUT->footer();
?>
