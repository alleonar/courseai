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
 * Here are API call and AI image CRUD functions.
 *
 *
 * @package    local_courseai_elt
 * @copyright  2024 E-Learning Touch
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @link       https://www.elearningtouch.com/
 *
 */

/**
 * Test function.
 *
 * @param any $var anything to test.
 */
function debug($var) {
    echo '<pre>';
    print_r($var);
    echo '</pre>'; die;
}

/**
 * Communicates with an AI API based on the configuration settings.
 *
 * This function sends a request to an AI service (either OpenAI or Mistral) based on the configuration
 * setting `processingai`. It constructs the appropriate API request, sends it using cURL, and
 * returns the response data.
 *
 * @param string $prompt The prompt or message to be sent to the AI model. This should be an array of messages
 *                       for the OpenAI API or a single string message for the Mistral API.
 *
 * @return array The response data from the API, decoded from JSON format. The exact structure of this data
 *               depends on the API's response format.
 *
 * @throws Exception Throws an exception with a descriptive error message if there is an issue with the API request
 *                   or if the HTTP status code is not 200.
 */
function local_courseai_elt_call_api($prompt) {

    global $CFG;

    $processingai = get_config('local_courseai_elt', 'processingai');
    $apikey = get_config('local_courseai_elt', 'apikey');

    if ($processingai === 'openai') {

        $endpoint = 'https://api.openai.com/v1/chat/completions';
        $cacert = 'C:\curl\cacert.pem';
        $curl = curl_init($endpoint);

        curl_setopt_array($curl,
            [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode(
                [
                    'model' => 'gpt-4o-2024-05-13',
                    'messages' => $prompt,

                    // For prompt message composed by a single string
                    // 'messages' => [
                    //     [
                    //         'role' => 'user',
                    //         'content' => $prompt,
                    //     ]
                    // ],
                    'response_format' => [
                        'type' => 'json_object',
                    ],
                    'max_tokens' => 500,
                    "temperature" => 0.2,
                ]),
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $apikey,
                ],
                CURLOPT_CAINFO => $cacert,
            ]
        );

        $response = curl_exec($curl);

        if ($response === false) {
            $error = curl_error($curl);
            curl_close($curl);
            die("Erreur lors de la communication avec l'API: $error");
        } else {
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            if ($httpcode !== 200) {
                die("Erreur lors de la communication avec l'API : HTTP $httpcode");
            }

            $responsedata = json_decode($response, true);

            return $responsedata;
        }
    } else if ($processingai === 'mistralai') {

        $endpoint = 'https://api.mistral.ai/v1/chat/completions';
        $cacert = 'C:\curl\cacert.pem';
        $curl = curl_init($endpoint);

        curl_setopt_array($curl,
            [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_HTTPHEADER =>
                [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $apikey,
                ],
                CURLOPT_CAINFO => $cacert,
                CURLOPT_POSTFIELDS => json_encode(
                    [
                        'model' => 'mistral-large-latest',
                        'messages' => [
                            [
                                'role' => 'user',
                                'content' => $prompt,
                            ],

                        ],
                        'response_format' => [
                            'type' => 'json_object',
                        ],
                        'max_tokens' => 1000,
                        'temperature' => 1,
                        'top_p' => 1,
                    ]
                ),
            ]
        );

        $response = curl_exec($curl);

        if ($response === false) {
            $error = curl_error($curl);
            curl_close($curl);
            die("Erreur lors de la communication avec l'API: $error");
        } else {
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            if ($httpcode !== 200) {
                die("Erreur lors de la communication avec l'API : HTTP $httpcode");
            }

            $responsedata = json_decode($response, true);

            return $responsedata;
        }
    }
}


/**
 * Parses and formats the AI response into an array structure.
 *
 * This function processes the response from an AI service, which contains course structure and description
 * information. It cleans and organizes the data into a structured array format, separating sections,
 * subsections, and activities, and removing unwanted characters.
 *
 * The expected format of the AI response is:
 * - Course structure and description separated by "/R/"
 * - Description of the course separated from extra notes or errors by "/E/"
 * - Sections of the course separated by "/K/"
 * - Subsections and activities separated by "/T/" and "/S/", respectively
 *
 * @param array $response The response from the AI service, typically an associative array containing
 *                        'choices' and 'message' keys. The 'content' key within 'message' contains the
 *                        structured text response from the AI.
 *
 * @return array An array representing the structured course data, where each element is an array or a
 *               string. The structure includes:
 *               - Course sections and subsections
 *               - Activities within each subsection
 *               - The course description as the last element of the array.
 *
 * @throws Exception Throws an exception if the response format is not as expected or if parsing fails.
 */
function local_courseai_elt_curl_to_array($response) {

    // Get the AI answer.
    $content = $response['choices'][0]['message']['content'];
    // Remove markdown.
    $answer = trim($content, "```json");
    // Clean extra caracters.
    $unwanted = ['"', '{', '}'];
    $answer = str_replace($unwanted, "", $answer);
    $answer = trim($answer);

    // Split course structure and course description.
    $aicourse = explode('/R/', $answer);

    // Clean extra description AI notes or AI error.
    $aicoursedescription = $aicourse[1];
    $aicoursedescription = explode('/E/', $aicoursedescription);
    $aicoursedescription = $aicoursedescription[0];

    // Split course structure into section.
    $answer = $aicourse[0];
    $answer = explode('/K/', $answer);

    // Formated answer array.
    $conceptarray = [];

    foreach ($answer as $concepts) {
        $concepts = trim($concepts);
        // Verify if not empty string due to trim.
        if ($concepts !== '') {
            $concept = explode("/T/", $concepts);
            // Verify if array or string.
            if (count($concept) > 1 && is_array($concept)) {
                $concept[1] = explode("/S/", $concept[1]);

                for ($subindex = 0; $subindex < count($concept[1]); $subindex ++) {
                    // Seperate each Sub section from it's activity.
                    $concept[1][$subindex] = explode("/A/", $concept[1][$subindex]);

                    // Clean activity string.
                    if (count($concept[1][$subindex]) > 1) {
                        $concept[1][$subindex][1] = trim(str_replace(")", "", $concept[1][$subindex][1]));
                    }
                }
            }
            $conceptarray[] = $concept;
        }
    }
    // Add description to the very last position.
    $conceptarray[] = $aicoursedescription;
    return $conceptarray;
}

/**
 * Execute the AI prompt and process the response.
 *
 * This function sends the given prompt to the AI API, processes the response, and extracts
 * the AI-generated course description.
 *
 * @param string $prompt The prompt to be sent to the AI API.
 * @return array The processed response from the AI API.
 */
function local_courseai_elt_execute_prompt($prompt) {

    $response = local_courseai_elt_call_api($prompt);
    $answer = local_courseai_elt_curl_to_array($response);

    return $answer;
}

/**
 * Validate the structure of the provided answer array.
 *
 * This function check if AI answer is wrong.
 * Specifically, it ensures that no section is named 'Key point' and no sub-point is
 * named 'Sub-point' or has the same name as its associated activity.
 *
 * @param array $answer The answer array to be validated. It should be an array of sections,
 *                      where each section is an array containing a string (section title)
 *                      and an array of modules. Each module is an array containing a sub-point title
 *                      and an activity.
 * @return bool Returns false if any section title is 'Key point' or any sub-point title
 *              is 'Sub-point' or is the same as its associated activity. Returns true otherwise.
 */
function local_course_ai_elt_validate_answer($answer) {
    
    foreach ($answer as $section) {

        if ($section[0] === 'Key point'){
            return false;
        }

        foreach ($section[1] as $module) {
            
            if ($module[0] === $module[1] || $module[0] === 'Sub-point') {
                return false;
            }
        }
    }
    return true;
}


/**
 * Retrieves the module ID for a given activity type from a reference array.
 *
 * This function looks up and returns the module ID associated with a specified activity type.
 * It uses a reference array where the keys are activity types and the values are module IDs.
 * If either the activity type or the reference array is not provided, an exception is thrown.
 *
 * @param string $activitytype The type of the activity for which the module ID is to be retrieved.
 *                              This should be a key in the $modulesref array.
 * @param array $modulesref An associative array where the keys are activity types and the values
 *                          are module IDs. This array is used to look up the module ID for the given
 *                          activity type.
 *
 * @return int The module ID corresponding to the provided activity type.
 *
 * @throws coding_exception Thrown if either the activity type or the reference array is not provided,
 *                          or if the activity type is not found in the reference array.
 */
function local_coursai_elt_get_module_id($activitytype, $modulesref) {

    if (!$modulesref || !$activitytype) {
        throw new coding_exception(get_string('codexmoduletype', 'local_courseai_elt'));
    }

    $moduleid = $modulesref[$activitytype];

    return $moduleid;
}


/**
 * Generates options for course structure by adding labels and quizzes at specified positions.
 *
 * This function modifies the course structure array by adding labels and quizzes based on the
 * provided settings. It can add labels to the start of each section and insert quizzes at the
 * beginning, end, or each section of the course structure.
 *
 * @param int $autolabel Determines whether to add a label at the start of each section.
 *                      A value of 1 indicates that labels should be added.
 * @param array $quizoccurence An associative array that specifies where quizzes should be added.
 *                              Possible keys are:
 *                              - 'None': No quizzes will be added.
 *                              - 'Each': A quiz will be added to each section.
 *                              - 'Start': A quiz will be added at the start of the course structure.
 *                              - 'End': A quiz will be added at the end of the course structure.
 * @param array $answer The course structure array where modifications are made. This array
 *                      contains sections and their content, which is modified to include labels
 *                      and quizzes as specified.
 * @param string $coursetitle The title of the course, used in the quiz added at the end of the
 *                             course structure.
 *
 * @return array The modified course structure array with added labels and quizzes.
 */
function local_courseai_elt_generate_options($autolabel, $quizoccurence, $answer, $coursetitle) {

    // LABEL PART.
    if ($autolabel === 1) {

        for ($sectionindex = 0; $sectionindex < count($answer); $sectionindex++) {

            $sectionlabel =
            [
                get_string('modulehelp_label', 'local_courseai_elt'),
                'label',
            ];
            // Add label at the start of each section.
            array_unshift($answer[$sectionindex][1], $sectionlabel);
        }
    }

    // QUIZ PART.
    // Bool return analysis after JSON need to use integer comparison.
    if (empty($quizoccurence['None'])) {

        // Check quiz each section.
        if (!empty($quizoccurence['Each'])) {

            $quizdata = new stdClass();

            for ($sectionindex = 0; $sectionindex < count($answer); $sectionindex++) {

                $quizdata->sectiontitle = $answer[$sectionindex][0];

                $sectionquiz =
                [
                    get_string('sectionquiztitle', 'local_courseai_elt', $quizdata),
                    'quiz',
                ];

                // Add the quiz to the course structure.
                $answer[$sectionindex][1][] = $sectionquiz;
            }
        }

        // Check quiz start position.
        if (!empty($quizoccurence['Start'])) {

            $startingquiz =
            [
                get_string('startingquizsection', 'local_courseai_elt'),
                [
                    [
                        get_string('startingquiztitle', 'local_courseai_elt'),
                        'quiz',
                    ],
                ],
            ];
            // Add quiz to start.
            array_unshift($answer, $startingquiz);
        }

        // Check quiz end position.
        if (!empty($quizoccurence['End'])) {

            $stringdata = new stdClass();
            $stringdata->coursetitle = $coursetitle;

            $endingquiz =
            [
                get_string('endingquizsection', 'local_courseai_elt'),
                [
                    [
                        get_string('endingquiztitle', 'local_courseai_elt', $stringdata),
                        'quiz',
                    ],
                ],
            ];
            // Add quiz to end.
            $answer[] = $endingquiz;
        }
    }
    return $answer;
}

/**
 * API GEN IMAGES.
 */

/**
 * Deletes former images and files associated with a given course.
 *
 * This function removes all files from the 'overviewfiles' and 'image' areas
 * within the course context. It also cleans up corresponding database entries
 * related to these files.
 *
 * @param int $courseid The ID of the course for which old images and files should be deleted.
 *
 * @return void
 * @throws dml_exception If there is an error accessing or modifying the database.
 */
function local_courseai_elt_delete_former_images($courseid) {
    global $DB;

    $contextid = context_course::instance($courseid)->id;

    $fs = get_file_storage();

    // Get former course files.
    $formerfiles = $fs->get_area_files($contextid, 'course', 'overviewfiles', null, 'id', false);
    // Delete former course files.
    foreach ($formerfiles as $file) {
        $file->delete();
    }
    // Delete DB former course files entries.
    $DB->delete_records_select(
        'files',
        'contextid = ? AND component ="course" AND filearea = "overviewfiles"',
        [$contextid]);

    // Get former section files.
    $formersectionfiles = $fs->get_area_files($contextid, 'format_cards', 'image', null, 'id', false);
    // Delete former files.
    foreach ($formersectionfiles as $file) {
        $file->delete();
    }

    // Delete DB former files entries.
    $DB->delete_records_select(
        'files',
        'contextid = ? AND component = ? AND filearea = ?',
        [$contextid, 'format_cards', 'image']);
}


/**
 * Generates an image using OpenAI's DALL-E 3 API based on the provided prompt information.
 *
 * This function sends a request to the OpenAI API to generate an image based on the given prompt
 * and returns a list of generated images with their details. The request is made using cURL,
 * and the response is processed to extract relevant image information.
 *
 * @param stdClass $promptinfos An object containing information about the prompt. It should have
 *                              the following properties:
 *                              - type: The type of image to generate (used to retrieve a specific string).
 *                              - coursetitle: The title of the course to be used in the image details.
 *
 * @return array An associative array containing a list of generated images. The array has a key
 *               'list' which maps to an array of image details, where each image detail is an array
 *               with the following keys:
 *               - title: The title of the course.
 *               - thumbnail: The URL of the generated image.
 *               - thumbnail_width: The width of the thumbnail (fixed at 150).
 *               - thumbnail_height: The height of the thumbnail (fixed at 100).
 *               - size: The size of the image in bytes (fixed at 10000).
 *               - author: The name of the user who requested the image.
 *               - source: The URL of the generated image.
 *
 * @throws moodle_exception If there is an error in generating the image or processing the API response.
 */
function local_courseai_elt_generate_openai_image($promptinfos) {
    global $CFG, $USER;

    // Local certificate link. Not usefull on all browser.
    $cacert = 'C:\curl\cacert.pem';

    $apikey = get_config('local_courseai_elt', 'apikey');
    $url = 'https://api.openai.com/v1/images/generations';
    $authorization = "Authorization: Bearer " . $apikey;

    // API needed data.
    $data = [
        'model' => "dall-e-3",
        'prompt' => get_string('openaiimage' . $promptinfos->type, 'local_courseai_elt', $promptinfos),
        'n' => 1,
        'size' => '1024x1024',
    ];

    // Curl setup.
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', $authorization]);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_CAINFO, $cacert);

    // Curl execution.
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        $errormsg = curl_error($ch);
    }
    curl_close($ch);

    // Decoding curl response.
    $result = json_decode($result);

    if (empty($result->data)) {
        throw new moodle_exception('error', 'local_courseai_elt', '', null, get_string('openaiimageerror', 'local_courseai_elt'));
    }

    $list = [];

    if (!empty($result->data)) {
        foreach ($result->data as $imagedata) {
            $list[] = [
                'title' => $promptinfos->coursetitle,
                'thumbnail' => $imagedata->url,
                'thumbnail_width' => 150,
                'thumbnail_height' => 100,
                'size' => 10000,
                'author' => $USER->firstname . ' ' . $USER->lastname,
                'source' => $imagedata->url,
            ];
        }
    }

    return ['list' => $list];
}

/**
 * Generates and stores an image for a course in Moodle.
 *
 * Downloads an image from the specified URL and saves it in the course's file area.
 *
 * @param int $courseid The ID of the course where the image will be stored.
 * @param string $imagename The name of the image file including its extension (e.g., 'image.png').
 * @param string $imageurl The URL from which the image will be downloaded.
 *
 * @throws moodle_exception If the image cannot be downloaded or the downloaded image data is too small.
 */
function local_courseai_elt_gen_img_course($courseid, $imagename, $imageurl) {
    global $USER, $DB;

    // Set parameters.
    $contextid = context_course::instance($courseid)->id;
    $userid = $USER->id;

    // Define file container.
    $filerecord = [
        'contextid' => $contextid,
        'component' => 'course',
        'filearea' => 'overviewfiles',
        'itemid' => 0,
        'filepath' => '/',
        'filename' => $imagename,
        'userid' => $userid,
        'source' => $imagename,
    ];

    // Download image.
    $imagedata = file_get_contents($imageurl);
    if (!$imagedata) {
        throw new moodle_exception('error', 'local_courseai_elt', '', null, 'Failed to download the image.');
    }

    // Check if image file is valid.
    if (strlen($imagedata) < 10) {
        throw new moodle_exception('error', 'local_courseai_elt', '', null, 'Downloaded image data is too small.');
    }

    // Download and create file in final area.
    $fs = get_file_storage();
    $fs->create_file_from_string($filerecord, $imagedata);
}

/**
 * Generates and stores an image for a section in the format_cards plugin for a course.
 *
 * Downloads an image from the specified URL and saves it in the file area designated for section images
 * within the format_cards plugin.
 *
 * @param int $courseid The ID of the course where the section belongs.
 * @param int $sectionid The ID of the section for which the image is to be stored.
 * @param string $imagename The name of the image file including its extension (e.g., 'section-image.png').
 * @param string $imageurl The URL from which the image will be downloaded.
 *
 * @throws moodle_exception If the image cannot be downloaded or if the downloaded image data is too small.
 */
function local_courseai_elt_gen_img_section($courseid, $sectionid, $imagename, $imageurl) {
    global $USER, $DB;

    // Set parameters.
    $contextid = context_course::instance($courseid)->id;
    $userid = $USER->id;

    $fs = get_file_storage();

    // // Get former files.
    // $formerfiles = $fs->get_area_files($contextid, 'format_cards', 'image', null, 'id', false);
    // // Delete former files.
    // foreach ($formerfiles as $file) {
    //     $file->delete();
    // }
    // // Delete DB former files entries.
    // $DB->delete_records_select(
    //     'files',
    //     'contextid = ? AND component = ? AND filearea = ?',
    //     [$contextid, 'format_cards', 'image']);

    // Define file container.
    $filerecord = [
        'contextid' => $contextid,
        'component' => 'format_cards',
        'filearea' => 'image',
        'itemid' => $sectionid,
        'filepath' => '/',
        'filename' => $imagename,
        'userid' => $userid,
        'source' => $imagename,
    ];

    // Download image.
    $imagedata = file_get_contents($imageurl);
    if (!$imagedata) {
        throw new moodle_exception('error', 'local_courseai_elt', '', null, 'Failed to download the image.');
    }

    // Check if image file is valid.
    if (strlen($imagedata) < 10) {
        throw new moodle_exception('error', 'local_courseai_elt', '', null, 'Downloaded image data is too small.');
    }

    // Download and create file in final area.
    $fs = get_file_storage();
    $fs->create_file_from_string($filerecord, $imagedata);
}
