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
 * This plugin provides IA to help for course creation.
 * Here are moodle callback functions.
 *
 *
 * @package    local_courseai_elt
 * @copyright  2024 E-Learning Touch
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @link       https://www.elearningtouch.com/
 *
 */


/**
 * Extends the course navigation to add a link to the plugin page.
 *
 * This function modifies the course navigation menu to include a link to the
 * "Course AI" plugin page. It only adds the link if the user has the capability
 * to update the course and if the editing mode is active.
 *
 * @param navigation_node $navigation The navigation node object to which the link should be added.
 * @param stdClass $course The course object representing the current course.
 * @param context_course $context The context object for the course.
 *
 * @return void
 */
function local_courseai_elt_extend_navigation_course($navigation, $course, $context) {
    global $PAGE;

    if ($PAGE->course) {
        $context = context_course::instance($PAGE->course->id);
        // Check if the user has the capability to update the course
        // and if the editing mode is active.
        if (has_capability('moodle/course:update', $context) && $PAGE->user_is_editing()) {
            // Create the URL to the plugin page, passing the course ID as a parameter.
            $url = new moodle_url('/local/courseai_elt/index.php', ['courseid' => $course->id]);

            // Add a navigation node with the text "Course AI" and the URL.
            $navigation->add(
                get_string('pluginname', 'local_courseai_elt'),
                $url,
                navigation_node::TYPE_SETTING,
                null,
                'courseailink'
            );
        }
    }
}


/**
 * Extends the site navigation to add a link to the plugin page.
 *
 * This function modifies the site navigation menu to include a link to the
 * "Course AI" plugin page. It only adds the link if the user has the capability
 * to update the course and if the editing mode is active. (NOT WORKING)
 *
 * @param navigation_node $navigation The navigation node object to which the link should be added.
 *
 * @return void
 */
function local_courseai_elt_extend_navigation($navigation) {
    global $PAGE;

    // Check if the user has the capability to update the course
    // and if the editing mode is active.
    if (has_capability('moodle/course:update', $PAGE->context) && $PAGE->user_is_editing()) {
        // Create the URL to the plugin page, passing the course ID as a parameter.
        $url = new moodle_url('/local/courseai_elt/index.php', ['courseid' => $PAGE->course->id]);

        // Add a navigation node with the text "Course AI" and the URL.
        $navigation->add(
            get_string('pluginname', 'local_courseai_elt'),
            $url,
            navigation_node::TYPE_SETTING,
            null,
            'mycourseailink'
        );
    }
}
