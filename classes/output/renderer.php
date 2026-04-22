<?php
namespace local_mycoursesux\output;

use plugin_renderer_base;

defined('MOODLE_INTERNAL') || die();

class renderer extends plugin_renderer_base {

    public function render_courses($courses, $layout) {

        $totals = [
            'totalcourses' => 0,
            'inprogress' => 0,
            'completed' => 0,
            'notstarted' => 0,
            'pendingactivities' => 0,
            'estimatedminutes' => 0,
            'notracking' => 0 // 👈 NUEVO
        ];

        foreach ($courses as $course) {

            $totals['totalcourses']++;

            if (!empty($course['inprogress'])) {
                $totals['inprogress']++;
            }

            if (!empty($course['iscompleted'])) {
                $totals['completed']++;
            }

            if (!empty($course['notstarted'])) {
                $totals['notstarted']++;
            }

            //  solo sumar si hay tracking real
            if (!empty($course['hastracking'])) {
                $totals['pendingactivities'] += $course['pendingactivities'] ?? 0;
                $totals['estimatedminutes'] += $course['estimatedminutes'] ?? 0;
            } else {
                $totals['notracking']++;
            }
        }

        return $this->render_from_template('local_mycoursesux/courses', [
            'courses' => $courses,

            // Layout activo
            'is_default' => $layout === 'default',
            'is_compact' => $layout === 'compact',
            'is_detailed' => $layout === 'detailed',
            'is_minimal' => $layout === 'minimal',
            'is_image' => $layout === 'image',

            // KPIs
            'totals' => $totals,

            // Flags útiles para UI
            'haspending' => $totals['pendingactivities'] > 0,
            'hastime' => $totals['estimatedminutes'] > 0,
            'hasnotracking' => $totals['notracking'] > 0
        ]);
    }
}