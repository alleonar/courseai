<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Validated course content form. Use to sanitize and pass data again to concept.php
 * for DB insertion.
 *
 * @package     local_courseai_elt
 * @copyright   2024 E-Learning Touch <https://www.elearningtouch.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_courseai_elt\form;

defined('MOODLE_INTERNAL') || die();

// Import template/config/lib files.
require_once($CFG->libdir . '/formslib.php');
use html_writer;
use pix_icon;

/**
 * New input form.
 * Defines title, context, length and level of the course.
 */
class course_form extends \moodleform {

    /**
     * Form body.
     */
    public function definition() {
        global $OUTPUT;
        $mform = $this->_form;
        // Curl response $answer.
        $customdata = $this->_customdata;

        // Full form section.
        if (isset($customdata) && is_array($customdata)) {

            // Check if form ready to record in DB (set by click on save /generate button).
            $mform->addelement('hidden', 'formvalid', 'false', ['id' => 'formvalid']);
            $mform->setType('formvalid', PARAM_BOOL);

            // The course id (set with custom data).
            $mform->addelement('hidden', 'courseid', $customdata['courseid']);
            $mform->setType('courseid', PARAM_INT);

            // The course title (set with custom data).
            $mform->addelement('hidden', 'newcoursetitle', $customdata['newcoursetitle']);
            $mform->setType('newcoursetitle', PARAM_TEXT);

            // The course students (set with custom data).
            $mform->addelement('hidden', 'newcoursepublic', $customdata['coursestudents']);
            $mform->setType('newcoursepublic', PARAM_TEXT);

            // The course difficulty level (ste with custom data).
            $mform->addelement('hidden', 'studentslevel', $customdata['studentslevel']);
            $mform->setType('studentslevel', PARAM_TEXT);

            // The course resume from ai api.
            $mform->addelement('hidden', 'aicoursedescription', $customdata['aicoursedescription']);
            $mform->setType('aicoursedescription', PARAM_TEXT);

            // Auto glossary option (set with custom data).
            $mform->addelement('hidden', 'autoglossary', $customdata['autoglossary']);
            $mform->setType('autoglossary', PARAM_BOOL);

            // Quiz array options (set with custom data).
            $mform->addelement('hidden', 'quizoccurence', $customdata['quizoccurence']);
            $mform->setType('quizoccurence', PARAM_RAW);

            // The course structure (set with js function save/generate course structure).
            $mform->addelement('hidden', 'coursestructurejson', '', ['id' => 'coursejsoncontainer']);
            $mform->setType('coursestructurejson', PARAM_RAW);

            // Check for generate all course on next page or save and get back to course/view (set by click on generate button).
            $mform->addelement('hidden', 'generatecourse', 'false', ['id' => 'generatecourse']);
            $mform->setType('generatecourse', PARAM_BOOL);

            // Check for generate course image(s) (set with custom data).
            $mform->addelement('hidden', 'generateimage', $customdata['generateimage']);
            $mform->setType('generateimage', PARAM_BOOL);

            // Image style options (set with custom data). NOT READY TO BE USED YET WITH DALL-E.
            // $mform->addelement('hidden', 'imagestyle', $customdata['imagestyle']);
            // $mform->setType('imagestyle', PARAM_TEXT);
        }
    }

    /**
     * Set form id.
     */
    public function definition_after_data() {
        $this->_form->setAttributes(['id' => 'coursestructureform', 'method' => 'post']);
    }
}
