<?php
namespace local_mycoursesux\service;

defined('MOODLE_INTERNAL') || die();

class course_service {

    public static function get_user_courses($userid) {
        global $DB, $CFG;

        $courses = enrol_get_users_courses($userid, true, '*');

        $data = [];

        foreach ($courses as $course) {

            // Progreso
            $progress = \core_completion\progress::get_course_progress_percentage($course);
            $progress = $progress ?? 0;

            // Último acceso
            $lastaccess = $DB->get_field(
                'user_lastaccess',
                'timeaccess',
                ['userid' => $userid, 'courseid' => $course->id]
            );

            $lastaccessformatted = $lastaccess
                ? userdate($lastaccess)
                : 'Sin actividad';

            // Estado
            if ($progress == 0) {
                $status = 'notstarted';
            } elseif ($progress == 100) {
                $status = 'completed';
            } else {
                $status = 'inprogress';
            }

            // URL del curso
            $courseurl = new \moodle_url('/course/view.php', [
                'id' => $course->id
            ]);

            // Continuar (simple por ahora)
            $continueurl = $courseurl;

            // =========================
            //  IMAGEN DEL CURSO
            // =========================

            $context = \context_course::instance($course->id);
            $fs = get_file_storage();

            $files = $fs->get_area_files(
                $context->id,
                'course',
                'overviewfiles',
                0,
                'sortorder, itemid, filepath, filename',
                false
            );

            $imageurl = null;

            if (!empty($files)) {
                foreach ($files as $file) {
                    if ($file->is_valid_image()) {
                        $imageurl = file_encode_url(
                            "$CFG->wwwroot/pluginfile.php",
                            '/' . $file->get_contextid() .
                            '/' . $file->get_component() .
                            '/' . $file->get_filearea() .
                            $file->get_filepath() .
                            $file->get_filename()
                        );
                        break;
                    }
                }
            }

            // =========================
            //  BASE DEL CURSO
            // =========================

            $courseitem = [
                'id' => $course->id,
                'fullname' => $course->fullname,
                'progress' => round($progress),

                'lastaccess' => $lastaccessformatted,
                'status' => $status,

                'courseurl' => $courseurl->out(),
                'continueurl' => $continueurl->out(),

                'iscompleted' => $status === 'completed',
                'inprogress' => $status === 'inprogress',
                'notstarted' => $status === 'notstarted',
            ];

            // =========================
            //  IMAGEN O FALLBACK
            // =========================

            if ($imageurl) {

                $courseitem['hasimage'] = true;
                $courseitem['image'] = $imageurl;

            } else {

                // Inicial segura (UTF-8)
                $initial = strtoupper(mb_substr(trim($course->fullname), 0, 1));

                // Paleta de colores
                $colors = [
                    ['#667eea', '#764ba2'],
                    ['#f093fb', '#f5576c'],
                    ['#4facfe', '#00f2fe'],
                    ['#43e97b', '#38f9d7'],
                    ['#fa709a', '#fee140'],
                    ['#30cfd0', '#330867']
                ];

                // Color basado en categoría (más consistente)
                $seed = $course->category ?? $course->id;
                $colorpair = $colors[$seed % count($colors)];

                $courseitem['hasimage'] = false;
                $courseitem['initial'] = $initial;
                $courseitem['colorstart'] = $colorpair[0];
                $courseitem['colorend'] = $colorpair[1];
            }

            $data[] = $courseitem;
        }

        return $data;
    }
}