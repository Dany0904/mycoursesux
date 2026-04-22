<?php
require('../../config.php');
require_login();

$PAGE->set_url('/local/mycoursesux/index.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_context(context_system::instance());
$PAGE->set_title('Mis cursos UX');
$PAGE->set_heading('Mis cursos UX');

$PAGE->requires->css('/local/mycoursesux/styles.css');
$PAGE->requires->js_call_amd('local_mycoursesux/main', 'init');

//  Obtener layout desde URL
$layout = optional_param('layout', '', PARAM_ALPHA);

//  Guardar SOLO si viene en URL
if (!empty($layout)) {
    set_user_preference('local_mycoursesux_layout', $layout);
}

//  Obtener preferencia (o default)
$currentlayout = get_user_preferences('local_mycoursesux_layout', 'default');

// Cargar renderer
$renderer = $PAGE->get_renderer('local_mycoursesux');

// Obtener datos
$courses = \local_mycoursesux\service\course_service::get_user_courses($USER->id);

// Render
echo $OUTPUT->header();
echo $renderer->render_courses($courses, $currentlayout);
echo $OUTPUT->footer();