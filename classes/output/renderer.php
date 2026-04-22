<?php
namespace local_mycoursesux\output;

use plugin_renderer_base;

defined('MOODLE_INTERNAL') || die();

class renderer extends plugin_renderer_base {

    public function render_courses($courses) {

        $layout = get_config('local_mycoursesux', 'cardlayout') ?? 'default';

        return $this->render_from_template('local_mycoursesux/courses', [
            'courses' => $courses,
            'is_default' => $layout === 'default',
            'is_compact' => $layout === 'compact',
            'is_detailed' => $layout === 'detailed',
            'is_minimal' => $layout === 'minimal',
            'is_image' => $layout === 'image',
        ]);
    }
}