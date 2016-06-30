<?php //$Id: mod_form.php,v 1.2.2.3 2009/03/19 12:23:11 mudrd8mz Exp $

/**
 * This file defines the main newmodule configuration form
 * It uses the standard core Moodle (>1.8) formslib. For
 * more info about them, please visit:
 *
 * http://docs.moodle.org/en/Development:lib/formslib.php
 *
 * The form must provide support for, at least these fields:
 *   - name: text element of 64cc max
 *
 * Also, it's usual to use these fields:
 *   - intro: one htmlarea element to describe the activity
 *            (will be showed in the list of activities of
 *             newmodule type (index.php) and in the header
 *             of the newmodule main page (view.php).
 *   - introformat: The format used to write the contents
 *             of the intro field. It automatically defaults
 *             to HTML when the htmleditor is used and can be
 *             manually selected if the htmleditor is not used
 *             (standard formats are: MOODLE, HTML, PLAIN, MARKDOWN)
 *             See lib/weblib.php Constants and the format_text()
 *             function for more info
 */

require_once($CFG->dirroot.'/course/moodleform_mod.php');

class mod_game_mod_form extends moodleform_mod {

    function definition() {

        global $COURSE;
        $mform =& $this->_form;

//-------------------------------------------------------------------------------
    /// Adding the "general" fieldset, where all the common settings are showed
        $mform->addElement('header', 'general', get_string('general', 'form'));

    /// Adding the standard "name" field
        $mform->addElement('text', 'name', get_string('gamename', 'game'), array('size'=>'64'));
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');

    /// Adding the required "intro" field to hold the description of the instance
        $mform->addElement('htmleditor', 'intro', get_string('gameintro', 'game'));
       $mform->setType('intro', PARAM_RAW);
       $mform->addRule('intro', get_string('required'), 'required', null, 'client');
		//$mform->setHelpButton('intro', array('writing', 'richtext'), false, 'editorhelpbutton');
	//$mform->addHelpButton('shuffleanswers', 'shuffleanswers', 'qtype_multichoice');

	    /// Adding the standard "name" field
        $mform->addElement('text', 'idgame', get_string('gameidgame', 'game'), array('size'=>'10'));
        $mform->setType('idgame', PARAM_INT);
        $mform->addRule('idgame', null, 'required', null, 'client');
        $mform->addRule('idgame', get_string('maximumchars', '', 10), 'maxlength', 10, 'client');

    /// Adding "introformat" field
    //    $mform->addElement('format', 'introformat', get_string('format'));

//-------------------------------------------------------------------------------
     // add standard elements, common to all modules
        $this->standard_coursemodule_elements();
//-------------------------------------------------------------------------------
        //add standard buttons, common to all modules
        $this->add_action_buttons();

    }
}

?>
