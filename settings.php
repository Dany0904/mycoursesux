<?php

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings = new admin_settingpage('local_mycoursesux', 'MyCourses UX');

    $settings->add(new admin_setting_configcheckbox(
        'local_mycoursesux/enabled',
        'Enable plugin',
        'Enable new courses UX',
        1
    ));

    $settings->add(new admin_setting_configselect(
        'local_mycoursesux/cardlayout',
        'Diseño de tarjetas',
        'Selecciona el diseño de cards',
        'default',
        [
            'default' => 'Default',
            'compact' => 'Compacto',
            'detailed' => 'Detallado',
            'minimal' => 'Minimal',
            'image' => 'Con imagen'
        ]
    ));

    $settings->add(new admin_setting_configcheckbox(
        'local_mycoursesux/enable_redirect',
        'Reemplazar vista de cursos',
        'Usar vista personalizada en lugar de /my/courses.php',
        1
    ));

    $ADMIN->add('localplugins', $settings);
}