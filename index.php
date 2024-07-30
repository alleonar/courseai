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
 * Base form to determine most of the generation options are here.
 * Media or file AI transcription and focus only is not yet available.
 *
 * @package     local_courseai_elt
 * @copyright   2024 E-Learning Touch <https://www.elearningtouch.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


// REQUIRE.
// Base folder with most functions needed.
require_once('../../config.php');
// File with langage switch option depending on user location.
require_once($CFG->dirroot. '/local/courseai_elt/lib.php');
// require_once('class/form/structure_form.php');
use local_courseai\form\structure_form;


// SET CONTEXT.
$courseid = required_param('courseid', PARAM_INT);

// Creating and setting the page context.
$context = context_course::instance($courseid);


// FILTER AND CAPABILITY CHECK.
// Check if user is connected to reach further functionalities.
require_login($courseid);
// Check if user is identified and not an anonymous guest.
if (isguestuser()) {
    throw new moodle_exception('noguest');
}

// Check capability. If test fail send an error.
$userid = $USER->id;
require_capability (
    'local/courseai:enterpage',
    $context,
    $userid,
    $doanything = true,
    $errormessage = get_string('courseai:permissions', 'local_courseai_elt'));
require_capability(
    'moodle/course:update',
    $context,
    $userid,
    $doanything = true,
    $errormessage = get_string('courseai:permissions', 'local_courseai_elt'));
require_capability(
    'moodle/course:manageactivities',
    $context,
    $userid,
    $doanything = true,
    $errormessage = get_string('courseai:permissions', 'local_courseai_elt'));

// PAGE SETTINGS.
$PAGE->set_url(new moodle_url('/local/courseai_elt/index.php'));
$PAGE->set_pagelayout('standard');
$PAGE->set_title($SITE->fullname);
$PAGE->set_heading(get_string('pluginname', 'local_courseai_elt'));


// VARIABLES.
// Structure form.
$customdata = [
        'courseid' => $courseid,
    ];
$processingurl = new moodle_url('/local/courseai_elt/processing/concepts.php');
$structureform = new \local_courseai_elt\form\structure_form($processingurl, $customdata);


// DISPLAY.
// Header.
echo $OUTPUT->header();

// Warning message.
echo html_writer::start_tag('div', ['class' => 'box py-3 generalbox alert alert-error alert alert-danger']);
echo html_writer::tag('p', get_string('warningbeforecreatecourse', 'local_courseai_elt'), ['class' => 'card-text']);
echo html_writer::end_tag('div');

// Structure form.
$structureform->display();

// Warning message.
echo html_writer::start_tag('div', ['class' => 'box py-3 generalbox alert alert-error alert alert-danger']);
echo html_writer::tag('p', get_string('warningbeforecreatecourse', 'local_courseai_elt'), ['class' => 'card-text']);
echo html_writer::end_tag('div');

// Footer, end of body, end of html.
echo $OUTPUT->footer();
