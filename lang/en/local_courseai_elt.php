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
 * Plugin strings are defined here.
 *
 * @package     local_courseai_elt
 * @category    string
 * @copyright   2024 E-Learning Touch <https://www.elearningtouch.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// General.
$string['pluginname'] = 'CourseAI';

// Errors and warnings.
$string['requiredfield'] = 'This field is required';
$string['structureissue'] = 'Problem encountered with response. Try again later.';
$string['courseai:enterpage'] = 'Allow user to access AI structure generator page';
$string['courseai:permissions'] = 'No permissions';
$string['codexmoduletype'] = 'Module type unknown';
$string['warningbeforecreatecourse'] =
'Warning ! If you continue, your former course will be deleted.
 Every associated topics, activities and datas will be lost.';

// Admin settings.
$string['apikey'] = 'API Key';
$string['apikey_desc'] = 'Enter your API key';
$string['aitype'] = 'AI';
$string['aitype_desc'] = 'Select your AI model';
$string['mistralai'] = 'Mistral AI';
$string['openai'] = 'Open AI';

// Structure form options.
$string['yourcoursetitle'] = 'Enter your course title';
$string['yourcoursecontext'] = 'Enter the description of your course';
$string['yourcoursecontextfile'] = 'Add file to determine the context of your course';
$string['contextonly'] = 'Focus only on source file';
$string['yourcoursepublic'] = 'Who are your students ?';
$string['yourcourselang'] = 'Enter the course language';
$string['yourcourselength'] = 'Enter the desired number of section';
$string['yoursectionlength'] = 'Enter the desired number of modules';
$string['yourcourseobjectives'] = 'Describe the objectives of this course';
$string['quizoccurence'] = 'Select the number of quiz';
$string['quiznone'] = 'None';
$string['quizstart']  = 'Start';
$string['quizeach'] = 'Each section';
$string['quizend'] = 'End';
$string['sectionquiztitle'] = 'Test: {$a->sectiontitle}';
$string['startingquizsection'] = 'Test your knowledge';
$string['startingquiztitle'] = 'Test: What do you already know ?';
$string['endingquizsection'] = 'Final exam';
$string['endingquiztitle'] = 'Final exam: {$a->coursetitle}';
$string['autolabel'] = 'Start section with label';
$string['autoglossary'] = 'Add glossary in general section';
$string['glossary'] = 'Section glossary';
$string['imagecheck'] = 'Generate images';
$string['imagestyle'] = 'Choose the image style';

// Students level radio.
$string['yourstudentslevel'] = "What's their level ?";
$string['beginner'] = 'Beginner';
$string['intermediate'] = 'Intermediate';
$string['expert'] = 'Expert';

// Default options.
$string['defaultpublic'] = 'students';
$string['nocoursecontext'] = '';
$string['nocourseobjectives'] = '';

// Course form.
$string['conceptsintro'] =
'This is your course structure. You can modify, add or remove fields.
 Once submit, your course will be generated.';

// Buttons.
$string['addnewsection'] = 'Add a new section';
$string['deletesection'] = 'Delete section';
$string['addnewactivity'] = 'Add a new activity';
$string['generatebuttonname'] = 'Generate';

// Module select name.
$string['modulename_label'] = 'Label';
$string['modulename_page'] = 'Page';
$string['modulename_url'] = 'URL';
$string['modulename_forum'] = 'Forum';
$string['modulename_glossary'] = 'Glossary';
$string['modulename_quiz'] = 'Quiz';

// Waiting modal.
$string['waitmodaltitle'] = 'Please wait !';
$string['waitmodalbody'] = 'Course AI is creating a fantastic new course for your students !';

// General section label.
$string['generallabeltext'] =
'<p>&#128101; <strong>Public: </strong>{$a->coursestudents}</p>
<p>&#127891; <strong>Level: </strong>{$a->courselevel}</p>
<p>&#128216; <strong>Description: </strong>{$a->coursedescription}</p>
';

// General section forum.
$string['generalforumname'] = 'Announcements';

// Activities guide part.
$string['modulehelp_page'] =
'<p>Page is a simple web page within Moodle.</p>
<p>This module allows you to add text, images, videos, and links.
 It is useful for providing detailed information, instructions, or resources.</p>';

$string['modulehelp_label'] =
'Label is a short piece of text or media used to organize the course page.
 Insert labels between activities or resources to provide descriptions, headings, or instructions.';

$string['modulehelp_url'] =
'<p>URL is a link to an external website.</p>
<p>This module allows to direct students to useful web resources, articles, videos, or any online content.</p>';

$string['modulehelp_forum'] =
'<p>Forum is an online discussion board.</p>
<p>This module facilitates communication and discussion among students. You can use it for announcements, Q&A, or topic discussions.<p>';

$string['modulehelp_quiz'] =
'<p>Quiz is an online assessment tool.</p>
<p>This module is used to create quizzes with various question types (multiple choice, true/false, short answer).
 You can set time limits, allow multiple attempts, and provide feedback.</p>';

$string['modulehelp_glossary'] =
'<p>Glossary is a list of terms and definitions.</p>
<p>This module allows students to contribute by adding terms and definitions, making it a collaborative tool.
 It is great for building a course-specific dictionary.</p>';

/******************************************************************************************************************************************************************************* */

// IMAGE PROMPT PART.
// Prompt v1.
$string['openaiimagecourse'] =
'Can you generate an image to illustrate a course on {$a->coursetitle}?
 Do not add any unappropriate content';
// // The image must be in a {$a->imagestyle} style.';
$string['openaiimagesection'] =
'Can you generate an image to illustrate a chapter of a course on {$a->sectiontitle}?
 The course subject is {$a->coursetitle}.
 Do not add any unappropriate content';
// // The image must be in a {$a->imagestyle} style.';
$string['openaiimageerror'] = 'result is empty or prompt not respecting API terms of service and conditions';

/******************************************************************************************************************************************************************************* */

// TEXT PROMPT PART.
// Prompt v7 - english - must use temperature 0.3 or 0.2.
$string['openaiprompt_intro'] =
'Can you generate {$a->section} key points, each containing {$a->subsection} sub-points,
 for a comprehensive Moodle course at {$a->courselevel} level on {$a->coursetitle} for {$a->coursestudents}?
Also, suggest Moodle-compatible activities and resources to validate understanding of the key points.';

$string['openaiprompt_lang'] =
'Your answer must be in: {$a->courselang}';

$string['openaiprompt_context'] =
'The course description is: {$a->coursecontext}';

$string['openaiprompt_objectives'] =
'The course objectives are: {$a->courseobjectives}';

// Options for context files. Not used yet.
$string['openaiprompt_sourceonly'] = '';
$string['openaiprompt_files'] = '';

$string['openaiprompt_format'] =
'Your response should ONLY consist of a JSON containing key points in the following format:

(NO TITLE)
Key point/T/Sub-point/A/suggested activity/S/Sub-point/A/suggested activity/K/
Key point/T/Sub-point/A/suggested activity/S/Sub-point/A/suggested activity/K/
Key point/T/Sub-point/A/suggested activity/S/Sub-point/A/suggested activity
/R/ Short course content description of maximum 300 characters /E/
(NO COMMENTS)

"Key point" is a moodle section name (never generate the word "Key point").

"/K/" separates each key point.
"/T/" separates each key point and its sub-points.
"/S/" separates each sub-point.
"/A/" separates the sub-point name and its corresponding activity.
"/R/" separates the course outline and course description.
"/E/" marks the end of the description.

The activities should be only chosen from this list. Only the sub-points should have activities:
- forum,
- url,
- page.

Your response must not include separators like colons and commas, except for the short content description which can have standard punctuation.
Ensure to add both "/" for each separator as specified in the format.';

$string['openaiprompt_rules'] =
'Activity can only be a single word from the list.
 The description is unique and not repeated.
 This JSON must not contain any title, tabulation, unnecessary newlines, or spaces.
 Your response must not contain any notes, comments, or annotations.
 Your response must not contain the instructions provided in this prompt.
 Please verify that your response strictly adheres to the format and rules specified above.
 Do not include any titles, colons, or additional notes.';

// Prompt V5
// $string['openaiprompt_intro'] =
// 'Can you generate {$a->section} key points, each containing {$a->subsection} sub-points,
//  for a comprehensive Moodle course at {$a->courselevel} level on {$a->coursetitle} for {$a->coursestudents}?
//  Also, suggest Moodle-compatible activities and resources to validate understanding of the key points.
//  The activities should be chosen from this list. Only the sub-points should have activities:
//  (label, forum, url, page)';

// $string['openaiprompt_lang'] =
// 'Your response language must be: {$a->courselang}';

// $string['openaiprompt_context'] =
// 'The course description is: {$a->coursecontext}';

// $string['openaiprompt_objectives'] =
// 'The course objectives are: {$a->courseobjectives}';

// // Options for context files. Not used yet.
// $string['openaiprompt_sourceonly'] = '';
// $string['openaiprompt_files'] = '';

// $string['openaiprompt_format'] =
// 'Your response should ONLY consist of a JSON without a title containing key points in the following format:

// Key point/T/Sub-point/A/suggested activity/S/Sub-point/A/suggested activity/K/
// Key point/T/Sub-point/A/suggested activity/S/Sub-point/A/suggested activity/K/
// Key point/T/Sub-point/A/suggested activity/S/Sub-point/A/suggested activity
// /R/ Short course content description of maximum 350 characters /E/

// "/K/" separates each key point.
// "/T/" separates each key point and its sub-points.
// "/S/" separates each sub-point.
// "/A/" separates the sub-point name and its corresponding activity.
// "/R/" separates the course outline and course description.
// "/E/" marks the end of the description.';

// $string['openaiprompt_rules'] =
// '"Key point" is a section name (you should not generate "key point").
// Activity can only be a single word from the list.
// The description is unique and not repeated.
// This JSON must not contain any title, tabulation, unnecessary newlines, or spaces.
// Your response must not include separators like colons and commas, except for the short content description which can have standard punctuation.
// Your response must not contain any notes, comments, or annotations.
// Your response must not repeat the instructions provided in this prompt.
// Ensure to add "/" as specified in the format.';


// Prompt v4 english
// $string['openaiprompt_intro'] =
// 'Can you generate {$a->section} key points, each containing {$a->subsection} sub-points,
//  for a comprehensive Moodle course at {$a->courselevel} level on {$a->coursetitle} for {$a->coursestudents}?
//  Also, suggest Moodle-compatible activities and resources to validate understanding of the key points.
//  The activities should be chosen from this list. Only the sub-points should have activities:
//  (label, forum, url, page)';

// $string['openaiprompt_context'] =
// 'The course description is: {$a->coursecontext}';

// $string['openaiprompt_objectives'] = '';
// 'The course objectives are: {$a->courseobjectives}';

// // Options for context files. Not used yet.
// $string['openaiprompt_sourceonly'] = '';
// $string['openaiprompt_files'] = '';

// $string['openaiprompt_format'] =
// 'Your response should ONLY consist of a JSON without a title containing key points in the following format:

// Key point/T/Sub-point/A/suggested activity/S/Sub-point/A/suggested activity/K/
// Key point/T/Sub-point/A/suggested activity/S/Sub-point/A/suggested activity
// /R/ Short course content description of maximum 350 characters /E/

// "/K/" separates each key point.
// "/T/" separates each key point and its sub-points.
// "/S/" separates each sub-point.
// "/A/" separates the sub-point name and its corresponding activity.
// "/R/" separates the course outline and course description.
// "/E/" marks the end of the description.';

// $string['openaiprompt_rules'] =
// '"Key point" is a section name (you should not generate "key point").
// The description is unique and not repeated.
// This JSON must not contain any title, tabulation, unnecessary newlines, or spaces.
// Your response must not include separators like colons and commas, except for the short content description which can have standard punctuation.
// Your response must not contain any notes, comments, or annotations.
// Your response must not repeat the instructions provided in this prompt.
// Ensure to add "/" as specified in the format.';

// Prompt V3 English
// $string['coursepromptopenai'] =
// 'Can you generate {$a->section} key points, each containing {$a->subsection} sub-points,
//  for a comprehensive Moodle course at {$a->courselevel} level on {$a->coursetitle} for {$a->coursestudents}?
//  Also, suggest Moodle-compatible activities and resources to validate understanding of the key points.
//  The activities should be chosen from this list. Only the sub-points should have activities:

// (label, forum, url, page)

// Your response should ONLY consist of a JSON without a title containing key points in the following format:

// Key point/T/Sub-point/A/suggested activity/S/Sub-point/A/suggested activity/K/
// Key point/T/Sub-point/A/suggested activity/S/Sub-point/A/suggested activity
// /R/ Short course content description of maximum 350 characters /E/

// "/K/" separates each key point.
// "/T/" separates each key point and its sub-points.
// "/S/" separates each sub-point.
// "/A/" separates the sub-point name and its corresponding activity.
// "/R/" separates the course outline and course description.
// "/E/" marks the end of the description.

// "Key point" is a section name (you should not generate "key point").
// The description is unique and not repeated.
// This JSON must not contain any title, tabulation, unnecessary newlines, or spaces.
// Your response must not include separators like colons and commas, except for the short content description which can have standard punctuation.
// Your response must not contain any notes, comments, or annotations.
// Your response must not repeat the instructions provided in this prompt.
// Ensure to add "/" as specified in the format.';

// Prompt V3
// 'Peux-tu générer {$a->section} points clé contenant chacun {$a->subsection} sous-points clé pour un cours moodle complet
// de niveau {$a->courselevel} sur {$a->coursetitle} pour des {$a->coursestudents}. Propose égalemment des activités et
// ressources compatibles moodle pour valider la compréhension des points clés.
// Les activités doivent êtres choisies dans cette liste. Seuls les sous-point clés doivent avoir des activités:

// (label, forum, url, page)

// Ta réponse doit UNIQUEMENT comporter un JSON sans titre contenant des points clés sous le format suivant :

// Point clé/T/Sous-point clé/A/activité suggérée/S/Sous-point clé/A/activité suggérée/K/
// Point clé/T/Sous-point clé/A/activité suggérée/S/Sous-point clé/A/activité suggérée
// /R/ Courte description du contenu du cours de 350 caractères maximum /E/

// "/K/" correspond à un séparateur entre chaque point clé.
// "/T/" correspond à un séparateur entre chaque point clé et ses sous-points clés.
// "/S/" correspond à un séparateur entre chaque sous-point clé.
// "/A/" correspond à un séparateur entre le nom du sous-point clé et l\'activité correspondante.
// "/R/" correspond à un séparateur entre le plan de cours et la description du cours.
// "/E/" correspond à la fin de la description.
// "Point clé" correspond a un nom de section (tu ne dois pas générer "point clé").
// La description est unique et ne se répète pas.
// Ce JSON ne doit comporter aucun titre, tabulation, retour ligne ou espace inutile.
// Ta réponse ne doit pas inclure de séparateur comme les deux points et les virgules,
// sauf la courte description du contenu qui peut avoir une ponctuation standard.
// Ta réponse ne doit pas contenir de notes, commentaires ou annotations quelconques.
// Ta réponse ne doit pas rappeler les consignes donner dans ce prompt.
// Ajoute bien les "/" comme spécifié dans le format.';


// Prompt V2
// 'Peux-tu générer {$a->section} points clé contenant chacun {$a->subsection} sous-points clé pour un cours moodle complet
//  de niveau {$a->courselevel} sur {$a->coursetitle} pour des {$a->coursestudents}. Propose égalemment des activités et
//  ressources compatibles moodle pour valider la compréhension des points clés.
//  Les activités doivent êtres choisies dans cette liste. Seuls les sous-point clés doivent avoir des activités:

// (Zone de texte et Media, Test, Forum, URL)

// Ta réponse doit UNIQUEMENT comporter un JSON sans titre contenant des points clés sous le format suivant :

// "Point clé" : "Sous-point clé" ("activité suggérée")[SKP] "Sous-point clé" ("activité suggérée")[EKP]

// [EKP] correspond à un séparateur entre chaque point clé.
// [SKP] correspond à un séparateur entre chaque sous_point clé.
// Ce JSON ne doit comporter aucun titre, tabulation, retour ligne ou espace inutile.';
// et aucun indicateur de section ou de structure comme "point clé", "chapitre", "section"...
// Exemple de réponse pour un cours sur VERCINGETORIX:
// {"Origines de Vercingétorix": "Enfance et formation (Test)"[SKP] "Education politique (URL)"[EKP]};

// Prompt V1
// 'Peux-tu générer les points clés et sous-point clé selon toi à aborder dans
// un cours moodle de niveau {$a->courselevel} sur {$a->coursetitle} pour des {$a->coursestudents}.
// Propose égalemment des activités et ressources compatibles moodle pour valider la compréhension des points clés.
// Les activités doivent êtres choisies dans cette liste. Seuls les sous-point clés doivent avoir des activités:
// - Zone de texte et Media
// - Test
// - Forum
// - URL
// Ta réponse doit UNIQUEMENT comporter un JSON contenant des points clés sous le
// format de l\'exemple suivant :
// Point clé : Sous-point clé (activité suggérée) [SKP] Sous-point clé (activité suggérée)[EKP]
// [EKP] correspond à un séparateur entre chaque point clé.
// [SKP] correspond à un séparateur entre chaque sous_point clé.
// Ce JSON ne doit comporter aucune tabulation, retour ligne ou espace inutile.
// Tu peux générer le nombre de sous-points clés que tu juges nécessaires pour un
// cours TRES DETAILLE (tu n\'es pas obligé d\'en faire 2, c\'est juste un exemple).';

$string['coursepromptmistralai'] =
'Peux-tu me créer un plan de cours Moodle sur {$a->coursetitle} pour des {$a->coursestudents} de niveau {$a->courselevel}
 en utilisant le format suivant:
 [Nom de la section] suivi de "[T]", puis [Nom de l\'activité] suivi de "[A]" puis type de l\'activité suivi de "[S]"
 sauf le dernier type de chaque section. Les sections doivent être séparées par "[K]" sauf la dernière.
 La liste des types d\'activités que tu peux utiliser est: (Zone de texte et Media, Forum, URL, Page).

Contraintes supplémentaires:
 Le cours doit comporter {$a->section} sections contenant chacune {$a->subsection} activités,
 Pas de texte, de ponctuation, d\'indentation superflus,
 Pas de repères de sections, d\'activités ou autres
 Pas d\'éléments de la liste des types d\'activités dans [Nom de l\'activité],
 "[S]" ne doit pas être mis après le dernier type d\'activité de chaque section,
 "[K]" ne doit pas être mis après la toute dernière section.

Merci de me fournir le plan de cours complet en respectant ce format en JSON.';



