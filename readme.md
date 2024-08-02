# CourseAI Plugin - Documentation

## Introduction
- **Nom du Plugin**: CourseAI
- **Version**: 0.2.00
- **Auteur**: E-Learning Touch
- **Description**: CourseAI est un plugin Moodle permettant de générer automatiquement des structures de cours à l'aide de l'intelligence artificielle.

## Installation

### Prérequis
- Moodle version 4.1.11 LTS
- Clé API pour le service d'IA (OpenAI / Mistral)

### Étapes d'Installation
1. **Téléchargement**:
   - Téléchargez le plugin depuis le dépôt officiel ou le site de l'éditeur.

2. **Installation**:
   - Copiez le dossier `courseai` dans le répertoire `/local` de votre installation Moodle.
   - Connectez-vous à Moodle en tant qu'administrateur.
   - Allez dans l'administration du site et suivez les instructions pour terminer l'installation du plugin.

3. **Configuration**:
   - Allez dans Administration du site > Plugins > Plugins locaux > CourseAI.
   - Entrez votre clé API et configurez les autres paramètres selon vos besoins.
   - Choisir le modèle d'AI (OpenAI / Mistral).

## Utilisation

### Interface Utilisateur
- **Index**:
  - L'utilisateur remplit le formulaire structure_form pour définir les paramètres de base du cours (titre, description, public, langue, etc.).
  - Soumettre le formulaire renvoie sur processing/concepts.php.

- **Concepts**:
  - Arriver sur la page lance la complétion du prompt de texte et envoie la requête à l'API.
  - L'API renvoie la structure de cours sous forme d'une string.
  - La réponse est ensuite traitée et retournée sous forme d'array.
  - L'array est testé pour voir si les données correspondent à celles attendues, sinon une deuxième requête est envoyée.
  - Si les données ne sont toujours pas bonnes, renvoie une erreur. Sinon ajoute à la structure de cours les options.
  - Les données sont alors transmises au JS par data-attribute.
  - Le JS génère tous le formulaire contenant la structure de cours selon des templates (concepts.min.js).
  - L'utilisateur peut voir et ajuster la structure générée du cours, ajouter des sections ou des activités.
  - Quand l'utilisateur soumet le formulaire JS, celui ci recupère et sanitize une première fois les champs.
  - Le JS complète le course_form.php, affiche une modal d'attente et soumet le formulaire qui redirige sur concepts.php.
  - concepts.php récupère les données, les sanityze une seconde fois.
  - Les anciennes sections, activités et images sont supprimées.
  - Les nouvelles sont créés et les images sont générées par dall-e. Le tout est inséré dans la bdd.

  NB: Cette manipulation peut sembler complexe dans la façon de transmettre les données mais c'est le seul moyen d'utiliser
      le mform et le get_data de moodle. Au delà de la sécurité (avec passage de sesskey auto), le get_data n'est possible
      que sur un formulaire exactement similaire à celui du fichier _form.php (même pour les custom_data !).
      Utiliser un mix de javascript et de mform assure de cette façon flexibilité et sécurité.

- **Retour au Cours**:
  - Une fois les insertions terminées, purge les caches et redirige l'utilisateur vers la page du cours.

## Permissions et Capacités
- **Capabilities**:
  - `courseai:enterpage`: Permet à l'utilisateur d'accéder à la page du générateur de structure.
  - `course:update`: Vérifie que l'utilisateur peut bien mettre à jour la structure de cours.
  - `course:manageactivities`: Vérifie que l'utilisateur peut bien supprimer et créer des activités.

## Issues

**Course AI - Grunt**
Uglify fonctionne normalement.

JSHint (linter js) ne trouve pas le fichiers dans amd/src.
Besoin de passer à un autre linter si nécessaire.

Grunt watch ne fonctionne pas non plus. Besoin de lancer grunt à la main.
(Dans console de commande, faire grunt dans le repertoire root du plugin après chaque changement du fichier src/concepts.js).

**Course AI - Certificate**
Les certificats sont installés localement 'C:\curl\cacert.pem'.
Peux causer quelques soucis lors des requêtes aux API.
S'il ne sont pas nécessaires, commenter les lignes "cacert" et "CURLOPT_CAINFO" dans locallib.php.

**Course AI - chat gpt 4o**
Dans de très rares cas, l'api ne suit pas les directives. 
Veiller à garder une température entre 0.2 et 0.3 pour minimiser cette erreur.

**Course AI - dall-e-3**
L'API interprète de temps en temps certains prompt comme enfreignant les politiques d'utilisations.

## Roadmap

**Course AI - Suivit des tokens**
- Ajout d'un compteur de suivit des tokens sur page admin settings ?
- Ajout d'une table de suivit de consommation par les utilisateurs pour l'admin ?
- Ajout d'un compteur sur la page index qui estime l'eco-responsabilité d'une requete pour l'utilisateur:
  vert = requête modeste ; jaune = requête moyenne ; orange = requête lourde;

**Course AI - Génération de contenu**
- Ajout d'une fonction "baguette magique" sur la page de cours permettant de générer une activité 
  spécifique par IA (en edit mode, dans le menu "edit" (...) de l'activité).
- Ajout d'une fonction de génération de la totalité des activités depuis un bouton sur concepts.php.
- Ajouter une selection du style d'images générées ?

**Course AI - Injection forms de Moodle**

- Changement du format ? (cards...)
- Fournir un doc ?
- Fournir une vidéo ?
- Fournir un audio ?

**Course AI - data**
- (Facultatif) Déplacer les h5p Nolej + création des activités type h5p


# Autres infos.

## Format de réponse de requête

**Course AI - Curl response après traitement**
// $answer = [
//     [
//         'titre section 1',
//         [
//             [
//                 'titre sous section 11',
//                 'Test'
//             ],
//             [
//                 'Titre sous section 12',
//                 'URL'
//             ]
//         ],
//     ],
//     [
//         'titre section 2',
//         [
//             [
//                 'titre sous section 21',
//                 'Zone de texte et Media'
//             ],
//             [
//                 'Titre sous section 22',
//                 'Forum'
//             ]
//         ]
//     ],
//     [description du cours],
// ];

## Bibliographie 

 - [Externe - MaestrAI - Création de contenu de cours via AI](https://app.maestrai.com/)
 - [Externe - Coursefactory - Création de contenu de cours via AI](https://cob.coursefactory.net/)
 - [Externe - Edtake - Création de contenu de cours via AI](https://app.edtake.ai/)
 - [Plugin - Nolej - Création de contenu de cours via AI](https://nolej.io/)
 - [Externe - Open Ai - Pricing](https://openai.com/api/pricing/)
 - [Plugin - AI Text to questions generator- Fourni un texte, ça sort des questions](https://moodle.org/plugins/local_aiquestions)
 - [Plugin - AI Text to Image - Fourni un texte, ça sort des images](https://moodle.org/plugins/repository_txttoimg)
 - [Plugin - Compilatio Plagiarism and AI Content Detector- Advanced plagiarism detection tool](https://moodle.org/plugins/plagiarism_compilatio)
 - [Plugin - AI Connector - Plugin - Moodle to interface with AI services like ChatGPT, DALL-E, and Stable Diffusion for content generation](https://moodle.org/plugins/local_ai_connector) 
 - [Plugin - Chatbot - Chatbot format block | editeur 1](https://moodle.org/plugins/block_openai_chat)
 - [Plugin - Chatbot - Chatbot format block | editeur 2](https://moodle.org/plugins/block_ube_ta)
 - [Plugin - Chatbot - Chatbot format local](https://gitlab.elearningtouch.info/custom-development/plugin/local_touchbot_elt/-/tree/dev?ref_type=heads)
 - []()
 - []()

---
 
 ## Now enjoy
 
    $ Made with ❤️ by @Théo Potier and with ☕️ by @Alexandre Léonard.
 

 
