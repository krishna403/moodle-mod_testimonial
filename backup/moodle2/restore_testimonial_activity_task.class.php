<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * The mod_testimonial course module viewed event.
 *
 * @package mod_testimonial
 * @copyright 2014 Krishna Pratap Singh <krishna@vidyamantra.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/testimonial/backup/moodle2/restore_testimonial_stepslib.php'); // Because it exists (must)

/**
 * testimonial restore task that provides all the settings and steps to perform one
 * complete restore of the activity
 */
class restore_testimonial_activity_task extends restore_activity_task {

    /**
     * Define (add) particular settings this activity can have
     */
    protected function define_my_settings() {
        // No particular settings for this activity
    }

    /**
     * Define (add) particular steps this activity can have
     */
    protected function define_my_steps() {
        // testimonial only has one structure step
        $this->add_step(new restore_testimonial_activity_structure_step('testimonial_structure', 'testimonial.xml'));
    }
    /**
     * Define the contents in the activity that must be
     * processed by the link decoder
     */
    static public function define_decode_contents() {
        $contents = array();

        $contents[] = new restore_decode_content('testimonial', array('intro'), 'testimonial');

        return $contents;
    }
    
    static public function define_decode_rules() {
        $rules = array();

        $rules[] = new restore_decode_rule('TESTIMONIALVIEWBYID', '/mod/testimonial/view.php?id=$1', 'course_module');
        $rules[] = new restore_decode_rule('TESTIMONIALINDEX', '/mod/testimonial/index.php?id=$1', 'course');
        $rules[] = new restore_decode_rule('TESTIMONIALVIEWBYU', '/mod/testimonial/view.php?u=$1', 'testimonial');

        return $rules;

    }
    
      /**
     * Define the restore log rules that will be applied
     * by the {@link restore_logs_processor} when restoring
     * url logs. It must return one array
     * of {@link restore_log_rule} objects
     */
    static public function define_restore_log_rules() {
        $rules = array();

        $rules[] = new restore_log_rule('testimonial', 'view', 'view.php?id={course_module}', '{testimonial}');
        $rules[] = new restore_log_rule('testimonial', 'viewall', 'view.php?id={course_module}', '{testimonial}');
        $rules[] = new restore_log_rule('testimonial', 'add', 'view.php?id={course_module}', '{testimonial}');
        $rules[] = new restore_log_rule('testimonial', 'update', 'view.php?id={course_module}', '{testimonial}');
        

        return $rules;
    }
    
    
    /**
     * Define the restore log rules that will be applied
     * by the {@link restore_logs_processor} when restoring
     * course logs. It must return one array
     * of {@link restore_log_rule} objects
     *
     * Note this rules are applied when restoring course logs
     * by the restore final task, but are defined here at
     * activity level. All them are rules not linked to any module instance (cmid = 0)
     */
    static public function define_restore_log_rules_for_course() {
        $rules = array();

        // Fix old wrong uses (missing extension)
        $rules[] = new restore_log_rule('testimonial', 'view all', 'index?id={course}', null,
                                        null, null, 'index.php?id={course}');
        $rules[] = new restore_log_rule('testimonial', 'view all', 'index.php?id={course}', null);

        return $rules;
    }
}