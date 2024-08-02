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
 * This page has two functions :
 * Generate an answer from the prompt the teacher can modified before send
 * Generate course structure and images and insert them in DB
 *
 * @package     local_courseai_elt
 * @copyright   2024 E-Learning Touch <https://www.elearningtouch.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// REQUIRE.
// Base folder with most functions needed and database access.
require_once('../../../config.php');
require_once($CFG->dirroot.'/course/lib.php');
require_once($CFG->dirroot.'/course/modlib.php');
require_once($CFG->libdir.'/filelib.php'); 
require_once($CFG->dirroot . '/repository/lib.php');
require_once('../locallib.php');
// File with navigation extension and plugin function.
require($CFG->dirroot . '/local/courseai_elt/lib.php');
// Forms.
require_once($CFG->libdir . '/formslib.php');
use local_courseai_elt\form\course_form;


// SET CONTEXT.
// Creating and setting the moduleinfo context.
$courseid = required_param('courseid', PARAM_INT);
$context = context_course::instance($courseid);


// FILTER AND CAPABILITY CHECK.
// Check if user is connected to reach further functionalities.
require_login($courseid);
// Check if user is identified and not an anonymous guest.
if (isguestuser()) {
    throw new moodle_exception(get_string('courseai:permissions', 'local_courseai_elt'));
}
// Check if session key is valid.
require_sesskey();
// Check capabilities. If test fail send an error.
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
$PAGE->set_url(new moodle_url('/local/courseai_elt/processing/concepts.php'));
$PAGE->set_pagelayout('standard');
$PAGE->set_title($SITE->fullname);
$PAGE->set_heading(get_string('pluginname', 'local_courseai_elt'));


// STRUCTURE FORM DATA.
$coursetitle = required_param('newcoursetitle', PARAM_TEXT);
$courselang = optional_param('newcourselang', get_string('thislanguage', 'langconfig'), PARAM_TEXT);
$coursecontext = optional_param('newcoursecontext', '', PARAM_TEXT);
$courseobjectives = optional_param('newcourseobjectives', '', PARAM_TEXT);
$coursestudents = optional_param('newcoursepublic', get_string('defaultpublic', 'local_courseai_elt'), PARAM_TEXT);
$courselevel = optional_param('studentslevel', get_string('beginner', 'local_courseai_elt'), PARAM_TEXT);
$sectionnumber = optional_param('newcourselength', 3, PARAM_INT);
$subsectionnumber = optional_param('newsectionlength', 3, PARAM_INT);
$autoglossary = optional_param('autoglossary', false, PARAM_INT);
$autolabel = optional_param('autolabel', false, PARAM_INT);
$quizoccurence =
[
    'Start' => optional_param('quizstart', false, PARAM_INT),
    'Each' => optional_param('quizeach', false, PARAM_INT),
    'End' => optional_param('quizend', false, PARAM_INT),
];
$generateimage = optional_param('imagegenerationcheck', false, PARAM_INT);

// Imagestyle. NOT USED YET (see structure form).
// $imagestyle = optional_param('imagestyle', 'realistic', PARAM_TEXT);

// Focus on source file only check. NOT USED YET.
// if (optional_param('contextonly', 0, PARAM_INT) <= 0) {
//     $contextonly = false;
// } else {
//     $contextonly = true;
// }


// COURSE FORM DATA.
// Check if course form has been completed.
$formvalid = optional_param('formvalid', false, PARAM_BOOL);

// Check if form return with data.
if ($formvalid) {
    global $DB;
    
    // Instantiate form to access get_data (must add customdata to avoid moodle form error).
    $customdata = [
        'courseid' => '',
        'newcoursetitle' => '',
        'coursestudents' => '',
        'studentslevel' => '',
        'aicoursedescription' => '',
        'autoglossary' => '',
        'autolabel' => '',
        'quizoccurence' => '',
        'generateimage' => '',
        // 'imagestyle' => '',
    ];
    $courseform = new \local_courseai_elt\form\course_form('', $customdata);
    $fromform = $courseform->get_data();
    $courseid = $fromform->courseid;
    $courseid = clean_param($courseid, PARAM_INT);
    $newcourse = get_course($courseid);
    $generatecourse = $fromform->generatecourse;
    $aicoursedescription = $fromform->aicoursedescription;
    $aicoursedescription = clean_param($aicoursedescription, PARAM_TEXT);
    $autoglossary = $fromform->autoglossary;
    $quizoccurence = json_decode($fromform->quizoccurence, true);
    // Check quiz options format.
    if (!is_array($quizoccurence) || count($quizoccurence) !== 3) {
        throw new moodle_exception(get_string('wrongdatafromuser', 'local_courseai_elt'));
    }
    $generateimage = $fromform->generateimage;
    // $imagestyle = $fromform->imagestyle;
    // $imagestyle = clean_param($imagestyle, PARAM_TEXT);

    // Get the whole structure of the course.
    $coursestructuredata = json_decode($fromform->coursestructurejson, true);

    $courseformat = null;


    // COURSE STRUCTURE RESET.
    // Delete former course activities and make backup.
    $formermodules = $DB->get_records_select('course_modules', "course = ?", [$courseid]);
    foreach ($formermodules as $fm) {
        course_delete_module($fm->id);
    }
    // Delete former course section except General (protected).
    $DB->delete_records_select('course_sections', "course = ? AND section <> 0", [$courseid]);
    // Reset general sequence.
    $generalsequence = [];
    // Delete all former images.
    local_courseai_elt_delete_former_images($courseid);
    // Future If section modification instead use purge_section_cache_by_id.
    rebuild_course_cache($courseid, true);


    // GENERATE COURSE IMAGE.
    if ($generateimage === 1) {

        $coursepromptinfos = new stdClass();
        $coursepromptinfos->coursetitle = $coursetitle;
        $coursepromptinfos->sectiontitle = '';
        $coursepromptinfos->type = 'course';
        // $coursepromptinfos->style = $imagestyle;

        $imagesgenerated = local_courseai_elt_generate_openai_image($coursepromptinfos);
        $imageurl = $imagesgenerated['list'][0]['source'];

        // Add timestamp to name to make item unique and make identification more easy.
        // This way it can be replace without triggering backup image.
        local_courseai_elt_gen_img_course($courseid, $coursetitle . time() . '.png', $imageurl);
    }


    // GENERAL SECTION LABEL.
    // General label text content.
    $labeltextcontent = new stdClass();
    $labeltextcontent->coursestudents = $coursestudents;
    $labeltextcontent->courselevel = get_string($courselevel, 'local_courseai_elt');
    $labeltextcontent->coursedescription = $aicoursedescription;
    $labelcontent = get_string('generallabeltext', 'local_courseai_elt', $labeltextcontent);
    // Common infos.
    $labelinfo = new stdClass();
    $labelinfo->name = 'General Label';
    $labelinfo->intro = $labelcontent; // Enter data here.
    $labelinfo->introformat = FORMAT_HTML;
    $labelinfo->course = $courseid;
    $labelinfo->coursemodule = 0;
    $labelinfo->section = 0;
    $labelinfo->module = 14;
    $labelinfo->modulename = 'label';
    $labelinfo->instance = 0;
    $labelinfo->timecreated = time();
    $labelinfo->timemodified = 0;
    $labelinfo->visible = 1;
    $labelinfo->visibleoncoursepage = 1;
    $labelinfo->availabilityconditionsjson = '{"op":"&","c":[],"showc":[]}';
    $labelinfo->cmidnumber = '';
    // Create label.
    $generallabel = add_moduleinfo($labelinfo, $newcourse, null);
    $generallabelid = $generallabel->coursemodule;
    // Add label to first position of sequence.
    array_unshift($generalsequence, $generallabelid);


    // GENERAL SECTION FORUM.
    $foruminfo = new stdClass();
    $foruminfo->name = get_string('generalforumname', 'local_courseai_elt');
    $foruminfo->intro = 'General news and announcements';
    $foruminfo->introformat = FORMAT_HTML;
    $foruminfo->course = $courseid;
    $foruminfo->coursemodule = 0;
    $foruminfo->section = 0;
    $foruminfo->module = 10;
    $foruminfo->modulename = 'forum';
    $foruminfo->instance = 0;
    $foruminfo->timecreated = time();
    $foruminfo->timemodified = 0;
    $foruminfo->visible = 1;
    $foruminfo->visibleoncoursepage = 1;
    $foruminfo->availabilityconditionsjson = '{"op":"&","c":[],"showc":[]}';
    $foruminfo->cmidnumber = '';
    $foruminfo->type = 'general';
    $foruminfo->forcesubscribe = 1;
    $foruminfo->trackingtype = 0;
    $foruminfo->rsstype = 0;
    $foruminfo->rssarticles = 0;
    $foruminfo->grade_forum = 0;
    // Create Forum.
    $generalforum = add_moduleinfo($foruminfo, $newcourse, null);
    $generalforumid = $generalforum->coursemodule;
    // Add forum to last position of sequence.
    array_push($generalsequence, $generalforumid);


    // GENERAL SECTION GLOSSARY.
    if (!empty($autoglossary)) {

        // glossary specific infos
        $glossaryinfo = new stdClass();
        $glossaryinfo->name = get_string('modulename_glossary', 'local_courseai_elt');
        $glossaryinfo->intro = get_string('modulehelp_glossary', 'local_courseai_elt');
        $glossaryinfo->introformat = FORMAT_HTML;
        $glossaryinfo->course = $courseid;
        $glossaryinfo->coursemodule = 0;
        $glossaryinfo->section = 0;
        $glossaryinfo->module = 11;
        $glossaryinfo->modulename = 'glossary';
        $glossaryinfo->instance = 0;
        $glossaryinfo->timecreated = time();
        $glossaryinfo->timemodified = 0;
        $glossaryinfo->visible = 1;
        $glossaryinfo->visibleoncoursepage = 1;
        $glossaryinfo->availabilityconditionsjson = '{"op":"&","c":[],"showc":[]}';
        $glossaryinfo->cmidnumber = '';
        $glossaryinfo->grade = 0;
        $glossaryinfo->assessed = 0;
        $glossaryinfo->allowduplicatedentries = 0;
        $glossaryinfo->allowcomments = 0;
        $glossaryinfo->usedynalink = 0;
        $glossaryinfo->defaultapproval = 1;
        $glossaryinfo->displayformat = 'continuous';
        $glossaryinfo->approvaldisplayformat = 0;
        $glossaryinfo->globalglossary = 0;
        $glossaryinfo->entbypage = 10;
        $glossaryinfo->editalways = 1;
        $glossaryinfo->rsstype = 0;
        $glossaryinfo->rssarticles = 0;
        // Create Glossary.
        $generalglossary = add_moduleinfo($glossaryinfo, $newcourse, null);
        $generalglossaryid = $generalglossary->coursemodule;
        // Add glossary to sequence.
        array_push($generalsequence, $generalglossaryid);
    }

    // Get section id and sequence and update it.
    $generalsection = $DB->get_record_select('course_sections', "course = ? AND section = 0", [$courseid], '*', MUST_EXIST);
    $generalsection->sequence = implode(',', $generalsequence);
    $DB->update_record('course_sections', $generalsection);


    // SECTION AND MODULE CREATION.
    // Get module ids from db once (used in get_module_id function).
    $modulesref = $DB->get_records_menu('modules', null , '', 'name, id');
    // Iterate on all section to create them.
    for ($sectionposition = 1; $sectionposition <= count($coursestructuredata); $sectionposition ++) {
        // Create index to iterate through $coursestructuredata.
        $newsectionindex = $sectionposition - 1;
        // Sanitize data.
        $newsectionname = $coursestructuredata[$newsectionindex][0];

        if (empty($newsectionname)) {
            continue;
        }
        $newsectionname = clean_param($newsectionname, PARAM_TEXT);

        // Create section argument object.
        $newsection = new stdClass();
        $newsection->course = $courseid;
        $newsection->name = $newsectionname;
        $newsection->summary = '';
        $newsection->summaryformat = FORMAT_HTML;
        $newsection->section = $sectionposition;
        $newsection->sequence = 0;
        $newsection->visible = 1;
        $newsection->timemodified = time();

        // CREATE SECTION.
        $createdsection = $DB->insert_record('course_sections', $newsection, $returnid = true, $bulk = true);
        if ($createdsection) {

            // SECTION IMAGE.
            if ($newcourse->format === "cards" && $generateimage === 1) {

                $coursepromptinfos = new stdClass();
                $coursepromptinfos->coursetitle = $coursetitle;
                $coursepromptinfos->sectiontitle = $newsectionname;
                $coursepromptinfos->type = 'section';
                // $coursepromptinfos->style = $imagestyle;

                $imagesgenerated = local_courseai_elt_generate_openai_image($coursepromptinfos);

                $imageurl = $imagesgenerated['list'][0]['source'];
                local_courseai_elt_gen_img_section($courseid, $createdsection, $newsectionname .'.png', $imageurl);
            }

            // ACTIVITIES.
            $newactivities = $coursestructuredata[$newsectionindex][1];

            for ($activityposition = 0; $activityposition < count($newactivities); $activityposition ++) {

                // Get activity array (0: type, 1: name) and sanitize data.
                $newactivity = $newactivities[$activityposition];
                $newactivitytype = $newactivity[0];
                $newactivitytype = clean_param($newactivitytype, PARAM_TEXT);
                $newactivityname = $newactivity[1];
                $newactivityname = clean_param($newactivityname, PARAM_TEXT);
                $moduleid;

                // Get module id.
                if (!empty($newactivitytype)) {

                    $moduleid = local_coursai_elt_get_module_id($newactivitytype, $modulesref);

                    if (!$moduleid) {
                        throw new coding_exception('activity type unknown');
                    }
                }

                // Create object with all info needed by the module.
                // Common infos.
                $moduleinfo = new stdClass();
                $moduleinfo->name = $newactivityname;
                $moduleinfo->intro = get_string('modulehelp_' . $newactivitytype, 'local_courseai_elt');
                $moduleinfo->introformat = FORMAT_HTML;
                $moduleinfo->course = $courseid;
                $moduleinfo->coursemodule = 0;
                $moduleinfo->section = $sectionposition;
                $moduleinfo->module = $moduleid;
                $moduleinfo->modulename = $newactivitytype;
                $moduleinfo->instance = 0;
                $moduleinfo->timecreated = time();
                $moduleinfo->timemodified = 0;
                $moduleinfo->visible = 1;
                $moduleinfo->visibleoncoursepage = 1;
                $moduleinfo->availabilityconditionsjson = '{"op":"&","c":[],"showc":[]}';
                $moduleinfo->cmidnumber = '';

                // Check $newactivitytype and set $moduleinfo data accordingly.
                switch ($newactivitytype){
                    case 'quiz':
                        // Quiz specific infos.
                        $moduleinfo->quizpassword = ''; // Only use "quizpassword" or it will send an error in mod/quiz/lib.php.
                        $moduleinfo->grade = 100;
                        $moduleinfo->gradecat = 1; // Grade category id.
                        $moduleinfo->gradepass = 0;
                        $moduleinfo->reviewattempt = 65536;
                        $moduleinfo->reviewcorrectness = 0;
                        $moduleinfo->reviewmarks = 0;
                        $moduleinfo->reviewspecificfeedback = 0;
                        $moduleinfo->reviewgeneralfeedback = 0;
                        $moduleinfo->reviewrightanswer = 0;
                        $moduleinfo->reviewoverallfeedback = 0;
                        $moduleinfo->timeopen = 0;
                        $moduleinfo->timeclose = 0;
                        $moduleinfo->questiondecimalpoints = 2;
                        break;
                    case 'forum':
                        // Forum specific infos.
                        $moduleinfo->type = 'general';
                        $moduleinfo->forcesubscribe = 1;
                        $moduleinfo->trackingtype = 0;
                        $moduleinfo->rsstype = 0;
                        $moduleinfo->rssarticles = 0;
                        $moduleinfo->grade_forum = 1;
                        break;
                    case 'url':
                        // URL specific infos. CAN'T BE USE! NEED URL!
                        $moduleinfo->externalurl = '';
                        $moduleinfo->display = 0;
                        $moduleinfo->displayoptions = '{"printintro":1}';
                        break;
                    case 'label':
                        // Label specific modifications.
                        $moduleinfo->intro = $newactivityname;
                        break;
                    case 'page':
                        // Page specific infos.
                        $moduleinfo->content = '<p>This is your '. $newactivityname .' page module.</p>';
                        $moduleinfo->contentformat = FORMAT_HTML;
                        $moduleinfo->legacyfiles = 0;
                        $moduleinfo->legacyfileslast = null;
                        $moduleinfo->display = 0;
                        $moduleinfo->displayoptions = null;
                        $moduleinfo->printintro = 1;
                        $moduleinfo->printlastmodified = 1;
                        break;
                    case 'glossary':
                        // Glossary specific infos.
                        $moduleinfo->grade = 0;
                        $moduleinfo->assessed = 0;
                        $moduleinfo->allowduplicatedentries = 0;
                        $moduleinfo->allowcomments = 0;
                        $moduleinfo->usedynalink = 0;
                        $moduleinfo->defaultapproval = 1;
                        $moduleinfo->displayformat = 'continuous';
                        $moduleinfo->approvaldisplayformat = 0;
                        $moduleinfo->globalglossary = 0;
                        $moduleinfo->entbypage = 10;
                        $moduleinfo->editalways = 1;
                        $moduleinfo->rsstype = 0;
                        $moduleinfo->rssarticles = 0;
                        break;
                    default:
                        throw new coding_exception('Module type unvalid: ' . $newactivitytype . '(' . $moduleid . ')');
                }

                // Add module to course.
                $createdmodule = add_moduleinfo($moduleinfo, $newcourse, null);

                if (!$createdmodule) {
                    throw new coding_exception('error creating module n°_'.$sectionposition.'_'.$activityposition);
                }
            }
        } else {
            throw new coding_exception('error inserting sections in database');
        };
    }
    // Purge all caches for modifications to be effective.
    purge_all_caches();

    // REDIRECT TO COURSE PAGE.
    $courseurl = new moodle_url('../../../course/view.php', ['id' => $courseid]);
    redirect($courseurl);
}


// TEXT PROMPT CREATION.
// Get AI model to use.
$processingai = get_config('local_courseai_elt', 'processingai');

if (isset($processingai)) {

    $prompt = [];

    // Object to set prompt variables.
    $promptitems = new stdClass();
    $promptitems->coursetitle = $coursetitle;
    $promptitems->courselang = $courselang;
    $promptitems->courselevel = $courselevel;
    $promptitems->coursestudents = $coursestudents;
    $promptitems->section = $sectionnumber;
    $promptitems->subsection = $subsectionnumber;

    // Set prompt intro.
    $promptintro =
    [
        "role" => "system",
        "content" => get_string($processingai . "prompt_intro", "local_courseai_elt", $promptitems),
    ];
    $prompt[] = $promptintro;

    // Set course language.
    $promptlang =
    [
        "role" => "system",
        "content" => get_string($processingai . "prompt_lang", "local_courseai_elt", $promptitems),
    ];
    $prompt[] = $promptlang;

    // Set user course description if there's one.
    if ($coursecontext !== '') {
        $promptitems->coursecontext = $coursecontext;
        $promptcontext =
        [
            "role" => "system",
            "content" => get_string($processingai . "prompt_context", "local_courseai_elt", $promptitems),
        ];
        $prompt[] = $promptcontext;
    }

    // Set course objectives if there's some.
    if ($courseobjectives !== '') {

        $promptitems->courseobjectives = $courseobjectives;
        $promptobjectives =
        [
            "role" => "system",
            "content" => get_string($processingai . "prompt_objectives", "local_courseai_elt", $promptitems),
        ];
        $prompt[] = $promptobjectives;
    }

    // Set prompt format ($prompitems not use yet).
    $promptformat =
    [
        "role" => "system",
        "content" => get_string($processingai . "prompt_format", "local_courseai_elt", $promptitems),
    ];
    $prompt[] = $promptformat;

    // Set prompt additional rules ($prompitems not use yet).
    $promptrules =
    [
        "role" => "system",
        "content" => get_string($processingai . "prompt_rules", "local_courseai_elt", $promptitems),
    ];
    $prompt[] = $promptrules;

    // For prompt message composed by a single string
    // $promptmessage = 'courseprompt' . $processingai;
    // $prompt = get_string($promptmessage, 'local_courseai_elt', $promptitems);

} else {
    throw new coding_exception ('AI model unknown');
}


// AI API REQUEST.
// Instantiate var.
$aicoursedescription;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Execute request and prepare answer and description.
    $answer = local_courseai_elt_execute_prompt($prompt);
    // Extract description.
    $aicoursedescription = array_pop($answer);
    // Check course structure.
    $answervalidation = local_course_ai_elt_validate_answer($answer);
    // If not valid try again.
    if (!$answervalidation) {

        // Execute request and prepare answer and description.
        $answer = local_courseai_elt_execute_prompt($prompt);
        // Extract description.
        $aicoursedescription = array_pop($answer);
        // Check course structure.
        $answervalidation = local_course_ai_elt_validate_answer($answer);
    }
    // If still not valid sennd error.
    if (!$answervalidation) {
        throw new moodle_exception(get_string('structureissue', 'local_courseai_elt'));
    }
}

/******************************************************************************************************************************************************************************/

// Execute generate options function.
$structuredata = local_courseai_elt_generate_options($autolabel, $quizoccurence, $answer, $coursetitle);

// Get number of section to set add-section-btn id.
$sectioncount = count($structuredata) + 1;

// Must be converted to string to be validate by moodle form.
$quizdata = json_encode($quizoccurence);
// Instantiate form with data from prompt.
$customdata = [
    'courseid' => $courseid,
    'newcoursetitle' => $coursetitle,
    'coursestudents' => $coursestudents,
    'studentslevel' => $courselevel,
    'aicoursedescription' => $aicoursedescription,
    'autolabel' => $autolabel,
    'autoglossary' => $autoglossary,
    'quizoccurence' => $quizdata,
    'generateimage' => $generateimage,
    // 'imagestyle' => $imagestyle,

];
$courseform = new \local_courseai_elt\form\course_form('', $customdata);

// Link and transfer data to javascript with data attribute.
$answerjs = json_encode($structuredata);

// JS module call.
$PAGE->requires->js_call_amd('local_courseai_elt/concepts', 'init');



/******************************************************************************************************************************************************************************* */

// DISPLAY.
echo $OUTPUT->header();

// Hidden divs with data attribute as recommended by Moodle.
echo html_writer::start_tag('div', ['id' => 'dataanswerjs', 'data-answerjs' => $answerjs, 'class' => 'hidden']);
echo html_writer::end_tag('div');

// Warning message.
echo html_writer::start_tag('div', ['class' => 'box py-3 generalbox alert alert-error alert alert-danger']);
echo html_writer::tag('p', get_string('warningbeforecreatecourse', 'local_courseai_elt'), ['class' => 'card-text']);
echo html_writer::end_tag('div');

// Course title and instructions.
echo html_writer::tag('h1', $coursetitle);
echo html_writer::tag('p', get_string('conceptsintro', 'local_courseai_elt'));

// Container for form.
echo html_writer::start_tag('div', ['id' => 'sectionContainer']);
echo html_writer::end_tag('div');


// Add section button (need id for JS to follow section count).
echo html_writer::tag('button', get_string('addnewsection', 'local_courseai_elt'),
    [
        'type' => 'button',
        'class' => 'add-section-button btn btn-secondary',
        'id' => 'addSectBtn_'.$sectioncount,
    ]);

echo html_writer::start_tag('div', ['id' => 'submitContainer', 'class' => 'd-flex flex-wrap justify-content-center my-3']);
// Generate full course structure button.
echo html_writer::tag('button', get_string('generatebuttonname', 'local_courseai_elt'),
    [
        'type' => 'submit',
        'class' => 'add-section-button btn btn-primary mx-1',
        'id' => 'savebutton',
    ]);

// Generate full course button. Not usefull yet.
// echo html_writer::tag('button', 'Generate',
//     [
//         'type' => 'submit', 
//         'class' => 'add-section-button btn btn-primary mx-1',
//         'id' => 'generatebutton'
//     ]);

echo html_writer::end_tag('div');

$courseform->display();

// Warning message.
echo html_writer::start_tag('div', ['class' => 'box py-3 generalbox alert alert-error alert alert-danger']);
echo html_writer::tag('p', get_string('warningbeforecreatecourse', 'local_courseai_elt'), ['class' => 'card-text']);
echo html_writer::end_tag('div');

// Footer, end of body, end of html.
echo $OUTPUT->footer();
