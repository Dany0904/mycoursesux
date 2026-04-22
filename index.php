<?php
require('../../config.php');
require_login();

$PAGE->set_url('/local/mycoursesux/index.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_context(context_system::instance());
$PAGE->set_title('Mis cursos UX');
$PAGE->set_heading('Mis cursos UX');

$PAGE->requires->css('/local/mycoursesux/styles.css');

// Cargar renderer
$renderer = $PAGE->get_renderer('local_mycoursesux');

// Obtener datos
$courses = \local_mycoursesux\service\course_service::get_user_courses($USER->id);

// Render
echo $OUTPUT->header();
echo $renderer->render_courses($courses);
echo $OUTPUT->footer();