<?php
defined('MOODLE_INTERNAL') || die();

function local_mycoursesux_before_http_headers() {

    if (defined('AJAX_SCRIPT') && AJAX_SCRIPT) {
        return;
    }

    if (defined('CLI_SCRIPT') && CLI_SCRIPT) {
        return;
    }

    if (!get_config('local_mycoursesux', 'enable_redirect')) {
        return;
    }

    $requesturi = $_SERVER['REQUEST_URI'];

    if (strpos($requesturi, '/my/courses.php') !== false) {

        if (strpos($requesturi, '/local/mycoursesux/index.php') === false) {
            redirect(new moodle_url('/local/mycoursesux/index.php'));
        }
    }
}