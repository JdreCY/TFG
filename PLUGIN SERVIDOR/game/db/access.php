<?php 
defined('MOODLE_INTERNAL') || die();

$capabilities = array(
    'mod/game:viewmod' => array(
        'captype'      => 'read',
        'contextlevel' => CONTEXT_MODULE,
        'legacy'   => array(
            'teacher'        => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager'          => CAP_ALLOW
        )
    ),

	'mod/game:addinstance' => array(
		'captype'      => 'write',
		'contextlevel' => CONTEXT_COURSE,
		'legacy'   => array(
			'teacher'        => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW

		)
	),
    // Add more capabilities here ...
);
?>