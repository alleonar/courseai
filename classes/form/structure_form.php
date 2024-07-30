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
 * Course options form for AI generation.
 *
 * @package     local_courseai_elt
 * @copyright   2024 E-Learning Touch <https://www.elearningtouch.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_courseai_elt\form;

defined('MOODLE_INTERNAL') || die();


/**
 * Import template/config/lib files.
 */
require_once($CFG->libdir . '/formslib.php');

/**
 * New input form.
 * Defines title, context, length and level of the course.
 */
class structure_form extends \moodleform {

    /**
     * Form body.
     */
    public function definition() {
        $mform = $this->_form;
        $customdata = $this->_customdata;
        $course = get_course($customdata['courseid']);

        // Id.
        $mform->addElement('hidden', 'courseid', $customdata['courseid']);
        $mform->setType('courseid', PARAM_INT);

        // Title of Course.
        $mform->addElement('text', 'newcoursetitle', get_string('yourcoursetitle', 'local_courseai_elt'));
        $mform->setType('newcoursetitle', PARAM_TEXT);
        $mform->addRule('newcoursetitle', get_string('requiredfield', 'local_courseai_elt'), 'required', null, 'client');
        $mform->setDefault('newcoursetitle', $course->fullname);

        // Course language (default $user).
        $mform->addElement('text', 'newcourselang', get_string('yourcourselang', 'local_courseai_elt'));
        $mform->setType('newcourselang', PARAM_TEXT);
        $mform->setDefault('newcourselang', get_string('thislanguage', 'langconfig'));

        // Context/description of Course.
        $mform->addElement('textarea', 'newcoursecontext', get_string('yourcoursecontext', 'local_courseai_elt'));
        $mform->setType('newcoursecontext', PARAM_TEXT);

        // Objectives of Course.
        $mform->addElement('textarea', 'newcourseobjectives', get_string('yourcourseobjectives', 'local_courseai_elt'));
        $mform->setType('newcourseobjectives', PARAM_TEXT);

        // File for more accurate context.
        $maxbytes = get_config('moodle', 'maxbytes');
        $mform->addElement(
            'filepicker',
            'contextfile',
            get_string('yourcoursecontextfile', 'local_courseai_elt'),
            null,
            [
                'maxbytes' => $maxbytes,
                'accepted_types' => ['.jpg', '.png', '.pdf'],
            ]
        );

        // Focus only on sourcefile option.
        $mform->addElement('checkbox', 'contextonly', get_string('contextonly', 'local_courseai_elt'));
        $mform->setType('contextonly', PARAM_INT);

        // Students category.
        $mform->addElement('text', 'newcoursepublic', get_string('yourcoursepublic', 'local_courseai_elt'));
        $mform->setType('newcoursepublic', PARAM_TEXT);

        // Students level.
        $levelradioarray = [];
        $levelradioarray[] = $mform->createElement(
            'radio', 'studentslevel', '', get_string('beginner', 'local_courseai_elt'), 'beginner');
        $levelradioarray[] = $mform->createElement(
            'radio', 'studentslevel', '', get_string('intermediate', 'local_courseai_elt'), 'intermediate');
        $levelradioarray[] = $mform->createElement(
            'radio', 'studentslevel', '', get_string('expert', 'local_courseai_elt'), 'expert');
        $mform->addGroup($levelradioarray, 'studentslevelradio',
                get_string('yourstudentslevel', 'local_courseai_elt'), [' '], false);
        $mform->setDefault('studentslevel', 'beginner');

        // Number of sections of course.
        $mform->addElement('select', 'newcourselength', get_string('yourcourselength', 'local_courseai_elt'),
        [
            1 => '1',
            2 => '2',
            3 => '3',
            4 => '4',
            5 => '5',
            6 => '6',
            7 => '7+',
        ]);
        $mform->setType('newcourselength', PARAM_INT);
        $mform->setDefault('newcourselength', 3);

        // Number of sub section in section.
        $mform->addElement('select', 'newsectionlength', get_string('yoursectionlength', 'local_courseai_elt'),
        [
            1 => '1',
            2 => '2',
            3 => '3',
            4 => '4',
            5 => '5+',
        ]);
        $mform->setType('newsectionlength', PARAM_INT);
        $mform->setDefault('newsectionlength', 3);

        // Glossary checkbox.
        $glossarycheck[] = $mform->createElement('checkbox', 'autoglossary', '', '', 'class="pt-3"');
        $mform->setType('autoglossary', PARAM_INT);
        $mform->setDefault('autoglossary', 1);
        $mform->addGroup($glossarycheck, 'glossarycheck', get_string('autoglossary', 'local_courseai_elt'), [' '], false);

        // Labels checkbox.
        $labelcheck[] = $mform->createElement('checkbox', 'autolabel', '', '', 'class="pt-3"');
        $mform->setType('autolabel', PARAM_INT);
        $mform->setDefault('autolabel', 1);
        $mform->addGroup($labelcheck, 'labelcheck', get_string('autolabel', 'local_courseai_elt'), [' '], false);

        // Number of quiz checkbox.
        $quizcheck[] = $mform->createElement('advcheckbox', 'quizstart',
                get_string('quizstart', 'local_courseai_elt'), '', 'class="pt-2 pr-3"');
        $mform->setType('quizstart', PARAM_INT);
        $quizcheck[] = $mform->createElement('advcheckbox', 'quizeach',
                get_string('quizeach', 'local_courseai_elt'), '', 'class="pt-2 pr-3"');
        $mform->setType('quizeach', PARAM_INT);
        $quizcheck[] = $mform->createElement('advcheckbox', 'quizend',
                get_string('quizend', 'local_courseai_elt'), '', 'class="pt-2 pr-3"');
        $mform->setType('quizend', PARAM_INT);

        $mform->addGroup($quizcheck, 'quizcheck', get_string('quizoccurence', 'local_courseai_elt'), [' '], false);

        // Glossary checkbox.
        $imagecheck[] = $mform->createElement('checkbox', 'imagegenerationcheck', '', '', 'class="pt-3"');
        $mform->setType('imagegenerationcheck', PARAM_INT);
        $mform->addGroup($imagecheck, 'imagecheck', get_string('imagecheck', 'local_courseai_elt'), [' '], false);

        // Image style. NOT READY TO BE USED YET WITH DALL-E.
        // $mform->addElement('select', 'imagestyle', get_string('imagestyle', 'local_courseai_elt'),
        // [
        //     'hyper realistic photograph' => 'Realistic',
        //     'modern' => 'Modern',
        //     'cartoon' => 'Cartoon',
        //     'sci-fi futuristic 3D picture' => 'Futuristic',
        //     'historic' => 'Historic',
        // ]);
        // $mform->setType('imagestyle', PARAM_TEXT);
        // $mform->setDefault('imagestyle', 'hyper realistic photograph');

        // Submit button.
        $submitlabel = get_string('submit');
        $mform->addElement('submit', 'submitmessage', $submitlabel, 'class="mt-3"');
    }

    /**
     * Validation function.
     *
     * @param stdClass $data The $result of the GET
     * @param stdClass||null $files
     */
    public function validation($data, $files) {
        $errors = [];
        if (isset($data['purgeselectedcaches']) && empty(array_filter($data['purgeselectedoptions']))) {
            $errors['purgeselectedoptions'] = get_string('purgecachesnoneselected', 'admin');
        }
        return $errors;
    }
}

