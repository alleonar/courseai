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

// Général.
$string['pluginname'] = 'CourseAI';

// Erreurs et avertissements.
$string['requiredfield'] = 'Ce champ est requis';
$string['wrongdatafromuser'] = 'Les options sélectionnées ne sont pas valides';
$string['structureissue'] = 'Problème rencontré avec la réponse. Veuillez réessayer plus tard.';
$string['courseai:enterpage'] = 'Autoriser l\'utilisateur à accéder à la page du générateur de structure AI';
$string['courseai:permissions'] = 'Pas de permissions';
$string['codexmoduletype'] = 'Type de module inconnu';
$string['warningbeforecreatecourse'] =
'Avertissement ! Si vous continuez, votre ancien cours sera supprimé.
 Tous les sujets, activités et données associés seront perdus.';

// Paramètres administratifs.
$string['apikey'] = 'Clé API';
$string['apikey_desc'] = 'Entrez votre clé API';
$string['aitype'] = 'IA';
$string['aitype_desc'] = 'Sélectionnez votre modèle d\'IA';
$string['mistralai'] = 'Mistral AI';
$string['openai'] = 'Open AI';

// Options du formulaire de structure.
$string['yourcoursetitle'] = 'Entrez le titre de votre cours';
$string['yourcoursecontext'] = 'Entrez la description de votre cours';
$string['yourcoursecontextfile'] = 'Ajouter un fichier pour déterminer le contexte de votre cours';
$string['contextonly'] = 'Concentrez-vous uniquement sur le fichier source';
$string['yourcoursepublic'] = 'Qui sont vos étudiants ?';
$string['yourcourselang'] = 'Entrez la langue du cours';
$string['yourcourselength'] = 'Entrez le nombre souhaité de sections';
$string['yoursectionlength'] = 'Entrez le nombre souhaité de modules';
$string['yourcourseobjectives'] = 'Décrivez les objectifs de ce cours';
$string['quizoccurence'] = 'Sélectionnez le nombre de quiz';
$string['quiznone'] = 'Aucun';
$string['quizstart']  = 'Début';
$string['quizeach'] = 'Chaque section';
$string['quizend'] = 'Fin';
$string['sectionquiztitle'] = 'Test : {$a->sectiontitle}';
$string['startingquizsection'] = 'Testez vos connaissances';
$string['startingquiztitle'] = 'Test : Que savez-vous déjà ?';
$string['endingquizsection'] = 'Examen final';
$string['endingquiztitle'] = 'Examen final : {$a->coursetitle}';
$string['autolabel'] = 'Commencer la section avec une étiquette';
$string['autoglossary'] = 'Ajouter un glossaire dans la section générale';
$string['glossary'] = 'Glossaire de la section';
$string['imagecheck'] = 'Générer des images';
$string['imagestyle'] = 'Choisissez le style de l\'image';

// Radio pour le niveau des étudiants.
$string['yourstudentslevel'] = "Quel est leur niveau ?";
$string['beginner'] = 'Débutant';
$string['intermediate'] = 'Intermédiaire';
$string['expert'] = 'Expert';

// Options par défaut.
$string['defaultpublic'] = 'Etudiants';
$string['nocoursecontext'] = '';
$string['nocourseobjectives'] = '';

// Formulaire de cours.
$string['conceptsintro'] =
'Ceci est la structure de votre cours. Vous pouvez modifier, ajouter ou supprimer des champs.
 Une fois soumis, votre cours sera généré.';

// Boutons.
$string['addnewsection'] = 'Ajouter une nouvelle section';
$string['deletesection'] = 'Supprimer la section';
$string['addnewactivity'] = 'Ajouter une nouvelle activité';
$string['generatebuttonname'] = 'Générer';

// Nom des modules sélectionnés.
$string['modulename_label'] = 'Étiquette';
$string['modulename_page'] = 'Page';
$string['modulename_url'] = 'URL';
$string['modulename_forum'] = 'Forum';
$string['modulename_glossary'] = 'Glossaire';
$string['modulename_quiz'] = 'Quiz';

// Modal d'attente.
$string['waitmodaltitle'] = 'Veuillez patienter !';
$string['waitmodalbody'] = 'Course AI est en train de créer un cours fantastique pour vos étudiants!';
$string['waitmodalanimation'] =
'<div style="width: 100%; display: flex; justify-content: center; align-items: center">
<p id="modalanim" style="font-size: 3rem; display: inline-block; margin: auto">&#9881</p>
</div>';

// Modal de confirmation.
$string['confirmmodaltitle'] = 'Supprimer la section';
$string['confirmmodalbody'] = 'Etes vous sûr? Cette action ne peut pas être annulée.';
$string['confirmmodalbutton'] = 'Supprimer';

// Label de la section générale.
$string['generallabeltext'] =
'<p>&#128101; <strong>Public : </strong>{$a->coursestudents}</p>
<p>&#127891; <strong>Niveau : </strong>{$a->courselevel}</p>
<p>&#128216; <strong>Description : </strong>{$a->coursedescription}</p>
';

// Forum de la section générale.
$string['generalforumname'] = 'Annonces';

// Partie guide des activités.
$string['modulehelp_page'] =
'<p>Page est une page web simple dans Moodle.</p>
<p>Ce module vous permet d\'ajouter du texte, des images, des vidéos et des liens.
 Il est utile pour fournir des informations détaillées, des instructions ou des ressources.</p>';

$string['modulehelp_label'] =
'L\'étiquette est un court texte ou média utilisé pour organiser la page du cours.
 Insérez des étiquettes entre les activités ou les ressources pour fournir des descriptions, des titres ou des instructions.';

$string['modulehelp_url'] =
'<p>URL est un lien vers un site web externe.</p>
<p>Ce module permet de diriger les étudiants vers des ressources web utiles, des articles, des vidéos ou tout contenu en ligne.</p>';

$string['modulehelp_forum'] =
'<p>Forum est un tableau de discussion en ligne.</p>
<p>Ce module facilite la communication et la discussion entre les étudiants. Vous pouvez l\'utiliser pour les annonces, les questions-réponses ou les discussions sur des sujets.</p>';

$string['modulehelp_quiz'] =
'<p>Quiz est un outil d\'évaluation en ligne.</p>
<p>Ce module est utilisé pour créer des quiz avec différents types de questions (choix multiple, vrai/faux, réponse courte).
 Vous pouvez définir des limites de temps, permettre plusieurs tentatives et fournir des retours.</p>';

$string['modulehelp_glossary'] =
'<p>Glossaire est une liste de termes et définitions.</p>
<p>Ce module permet aux étudiants de contribuer en ajoutant des termes et des définitions, en faisant de lui un outil collaboratif.
 Il est idéal pour construire un dictionnaire spécifique au cours.</p>';

/******************************************************************************************************************************************************************************* */

// IMAGE PROMPT PART.
// Prompt v2.
$string['openaiimagecourse'] =
'Illustrate {$a->coursetitle}. No unappropriate or nsfw content.';
$string['openaiimagesection'] =
'{$a->sectiontitle} of {$a->coursetitle}. No unappropriate or nsfw content.';

// // Prompt v1.
// $string['openaiimagecourse'] =
// 'Can you generate an image to illustrate a course on {$a->coursetitle}?
//  Do not add any unappropriate content.The image must be in a {$a->imagestyle} style.';
// $string['openaiimagesection'] =
// 'Can you generate an image to illustrate a chapter of a course on {$a->sectiontitle}?
//  The course subject is {$a->coursetitle}. Do not add any unappropriate content.
//  The image must be in a {$a->imagestyle} style.';
$string['openaiimageerror'] = 'result is empty or prompt not respecting API terms of service and conditions';

/******************************************************************************************************************************************************************************* */

// TEXT PROMPT PART.
// Prompt v7 - english - must use temperature 0.3 or 0.2.
$string['openaiprompt_intro'] =
'Can you generate {$a->section} key points, each containing {$a->subsection} sub-points,
 for a comprehensive Moodle course at {$a->courselevel} level on {$a->coursetitle} for {$a->coursestudents}?
Also, suggest Moodle-compatible activities and resources to validate understanding of the key points.';

$string['openaiprompt_lang'] =
'Your response language must be: {$a->courselang}';

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
