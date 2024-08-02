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
 * Javascript file for genereting user form on concepts.php.
 * Send back data to course_form when submited.
 *
 * @package
 * @copyright   2024 E-Learning Touch <https://www.elearningtouch.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Declaration of the module.
define(['jquery', 'core/templates', 'core/modal_factory', 'core/modal_events', 'core/str'],
    function($, templates, ModalFactory, ModalEvents, Str) {
        
        // Init function.
        const init = async() => {

            // Get course structure from data-attribute.
            const DATA_ANSWER_JS = document.getElementById('dataanswerjs');
            const ANSWER_JS = DATA_ANSWER_JS.getAttribute('data-answerjs');
            // If failed send an error.
            if (!ANSWER_JS) {
                console.error('JSON is undefined');
                return;
            }
    
            // Get_strings.
            let waitModalTitle = await Str.get_string('waitmodaltitle', 'local_courseai_elt');
            let waitModalBody = await Str.get_string('waitmodalbody', 'local_courseai_elt');
            let waitModalAnimation = await Str.get_string('waitmodalanimation', 'local_courseai_elt');
            let confirmModalTitle = await Str.get_string('confirmmodaltitle', 'local_courseai_elt');
            let confirmModalBody = await Str.get_string('confirmmodalbody', 'local_courseai_elt');
            let confirmModalButton = await Str.get_string('confirmmodalbutton', 'local_courseai_elt');
            let moduleNamePage = await Str.get_string('modulename_page', 'local_courseai_elt');
            let moduleNameLabel = await Str.get_string('modulename_label', 'local_courseai_elt');
            let moduleNameForum = await Str.get_string('modulename_forum', 'local_courseai_elt');
            let moduleNameQuiz = await Str.get_string('modulename_quiz', 'local_courseai_elt');
            let moduleNameUrl = await Str.get_string('modulename_url', 'local_courseai_elt');
            let moduleNameGlossary = await Str.get_string('modulename_glossary', 'local_courseai_elt');
            let moduleAddButtonName = await Str.get_string('addnewactivity', 'local_courseai_elt');
            let sectionDeleteButtonName = await Str.get_string('deletesection', 'local_courseai_elt');
    
            // Waiting modal.
            const MODAL = await ModalFactory.create({
                title: waitModalTitle,
                body: waitModalBody,
                footer: waitModalAnimation,
            })

            // Confirm modal.
            const CONFIRM = await ModalFactory.create({
                type: ModalFactory.types.SAVE_CANCEL,
                title: confirmModalTitle,
                body: confirmModalBody,
            });
            // Change the save button label but not the value!
            CONFIRM.setButtonText('save', confirmModalButton);
            

            /**
             * Modal Animation function.
             * 
             * Allow HTML gear icon to rotate.
             */
            function modalGearRotate(){
                const rotatingGear = document.getElementById('modalanim');
                let angle = 0;
                setInterval(() => {
                    angle = (angle + 1) % 360;
                    rotatingGear.style.transform = `rotate(${angle}deg)`;
                }, 10);
            }
    
            /**
             * Section creation function.
             * 
             * Create a section with a container for activities.
             */
            function addSectionGroup() {
                const ADD_SECTION_BUTTON = document.getElementsByClassName('add-section-button');
    
                ADD_SECTION_BUTTON[0].addEventListener('click', (event) => {
                    let sectionName = event.target.id;
                    let sectionNumber = sectionName.split('_')[1];
    
                    // Data for template activitygroup.
                    let formData = {
                        id: 'fgroup_id_sectiongroup_' + sectionNumber,
                        groupname: 'sectiongroup_' + sectionNumber,
                        label: 'Section ' + sectionNumber,
                        element: {
                            elements: [
                                {
                                    input: true,
                                    name: "sectiontitle_" + sectionNumber,
                                    id: "sectiontitle_" + sectionNumber,
                                    value: '',
                                    size: '30',
                                    type: 'text',
                                    attributes: 'placeholder="Enter your text"',
                                    frozen: false,
                                    hardfrozen: false,
                                    error: null
                                },
                                {
                                    div: true,
                                    section: sectionNumber,
                                },
                                {
                                    button: true,
                                    name: 'addactivitybutton',
                                    id: sectionNumber + '_0',
                                    value: moduleAddButtonName,
                                    attributes: '',
                                    frozen: false,
                                    error: null
                                },
                                {
                                    removebutton: true,
                                    id: sectionNumber,
                                    value: sectionDeleteButtonName,
                                    attributes: '',
                                    frozen: false,
                                    error: null
                                },
                            ]
                        }
                    };
    
                    let containerPosition = event.target.id;
                    
                    templates.render('local_courseai_elt/sectiongroup', formData).done(function(html, js) {
                        $(`#${containerPosition}`).before(html);
                        templates.runTemplateJS(js);
                        
                        sectionNumber++;
                        event.target.id = 'addSectBtn_' + sectionNumber;
                        
                        removeSectionGroup();
                        removeActivityGroup();
                        addActivityGroup();
    
                    }).fail(function(err) {
                        console.error('Erreur lors du rendu du template:', err);
                    });
                });
            }
            // Initialize listener.
            addSectionGroup();
    
            /**
             * Remove section function.
             * 
             * Delete the DOM element "form-group" containing full section if confirm in modal.
             */
            function removeSectionGroup() {
                const DELETE_BUTTONS = document.querySelectorAll('.remove-section-button');
                let targetToDelete = null;
    
                DELETE_BUTTONS.forEach(deleteBtn => {
                    deleteBtn.addEventListener('click', (event) => {
                        targetToDelete = event.target.closest('.form-group');
                        CONFIRM.show();
                        CONFIRM.getRoot().on(ModalEvents.save, (e) => {
                            if (targetToDelete) {                     
                                targetToDelete.remove();
                                targetToDelete = null;
                            }
                        });
                    });
                });
            }
    
            /**
             * Add new activity group function.
             * 
             * Create an activity group in the dedicated section container.
             */
            // Array to follow activities listenner assignement to avoid duplication.
            let activityListenerList = [];
            function addActivityGroup() {
            
                const ADD_ACTIVITY_BUTTONS = document.getElementsByClassName('add-activity-button');
    
                Array.prototype.forEach.call(ADD_ACTIVITY_BUTTONS, function(addActivityBtn) {
    
                    // Check if button already have a listener or not.
                    if (!activityListenerList.includes(addActivityBtn)){
                        // Add button to listener list.
                        activityListenerList.push(addActivityBtn);
                        
                        addActivityBtn.addEventListener("click", function add_new_activity (event){
                            
                            // Instance.
                            let activityName;
                            let activityTypeName;
                            let sectionNumber;
                            let activityNumber;
                            
                            // Get position by getting button id.
                            let targetId = event.target.id;
                            targetIdSplit = targetId.split('_');
                            // Extract section number.
                            sectionNumber = targetIdSplit[1];
                            // Set activity number.
                            if (targetId[2] === '0') {
                                activityNumber = '1';
                            } else {
                                activityNumber = parseInt(targetIdSplit[2]) + 1;
                                activityNumber = activityNumber.toString(10);
                            };
                            
                            activityName = 'subsection_' + sectionNumber + '_' + activityNumber;
                            activityTypeName = 'activity_' + sectionNumber + '_' + activityNumber;
                            fullSubSectionName = 'fullsubsection_' + sectionNumber + '_' + activityNumber;
                            
                            // Data for template activitygroup.
                            let formData = {
                                element: {
                                    id: 'form_id',
                                    groupname: fullSubSectionName,
                                    label: fullSubSectionName,
                                    attributes: ["w-100"],
                                    elements: [
                                        {
                                            select: true,
                                            name: activityTypeName,
                                            id: activityTypeName,
                                            multiple: false,
                                            size: null,
                                            frozen: false,
                                            hardfrozen: false,
                                            error: null,
                                            attributes: '',
                                            options: [
                                                {
                                                    text: moduleNameLabel,
                                                    value: 'label',
                                                    selected: false,
                                                    disabled: false,
                                                    optionattributes: ''
                                                },
                                                {
                                                    text: moduleNamePage,
                                                    value: 'page',
                                                    selected: true,
                                                    disabled: false,
                                                    optionattributes: ''
                                                },
                                                {
                                                    text: moduleNameGlossary,
                                                    value: 'glossary',
                                                    selected: false,
                                                    disabled: false,
                                                    optionattributes: ''
                                                },
                                                {
                                                    text: moduleNameQuiz,
                                                    value: 'quiz',
                                                    selected: false,
                                                    disabled: false,
                                                    optionattributes: ''
                                                },
                                                {
                                                    text: moduleNameForum,
                                                    value: 'forum',
                                                    selected: false,
                                                    disabled: false,
                                                    optionattributes: ''
                                                },
                                                {
                                                    text: moduleNameUrl,
                                                    value: 'url',
                                                    selected: false,
                                                    disabled: false,
                                                    optionattributes: ''
                                                }
                                            ]
                                        },
                                        {
                                            input: true,
                                            name: activityName,
                                            id: activityName,
                                            value: '',
                                            size: '50',
                                            type: 'text',
                                            attributes: 'placeholder="Enter your text"',
                                            frozen: false,
                                            hardfrozen: false,
                                            error: null
                                        },
                                        {
                                            button: true,
                                            name: 'removebutton',
                                            id: '',
                                            value: '<i class="icon fa fa-trash fa-fw icon-class" aria-hidden="true"></i>',
                                            attributes: 'class="remove-button btn btn_primary"',
                                            frozen: false,
                                            error: null
                                        },
                                    ]
                                }
                            };
                            
                            // Set container DOM location.
                            let containerId = 'activityContainer_' + sectionNumber;
                            
                            // Load and display template before the add activity button.
                            templates.render('local_courseai_elt/activitygroup', formData).done(function(html, js) {
    
                                // Add HTML to container.
                                $(`#${containerId}`).append(html);
                                // Execute JS.
                                    templates.runTemplateJS(js);
                                    // Increment add activity button id to give new information on next click.
                                    event.target.id = 'addActBtn_' + sectionNumber + '_' + activityNumber;
                                    // Re-Initialize remove listeners after event.
                                    removeActivityGroup();
                                    
                                }).fail(function(err) {
                                    return 'Erreur lors du rendu du template:', err;
                            });
                        });
                    }
                });
            }
    
            /**
             * Remove activity group function.
             * 
             * Delete the DOM element "form-group" containing full activity.
             */
            function removeActivityGroup() {
    
                let DELETE_BUTTONS = document.getElementsByClassName('remove-button');
                Array.prototype.forEach.call(DELETE_BUTTONS, function(deleteBtn) {
                    deleteBtn.addEventListener('click', (event) => {
                        targetToDelete = event.target.closest('.form-group');
                        if (targetToDelete) {                     
                            targetToDelete.remove();
                            targetToDelete = null;
                        }
                    });
                });
            }
    
            /**
             * Get JS form data function.
             * 
             * Get all data from fields to sanitize them and prepare them for db insert.
             * The sanityze part is optionnal since all real cleaning part is done server side.
             */
            function getFormData () {
                
                // Use of gEBCN for following DOM change.
                const SECTION_FORM = document.getElementsByClassName('section-form');
    
                // Array for organised data return.
                let courseStructure = [];
    
                // Html clean function.
                function escapeHtml(text) {
                    let map = {
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#039;',
                        '/': '&#x2F;',
                        '`': '&#x60;',
                        '=': '&#x3D;'
                    };
                    return text.replace(/[&<>"'`=\/]/g, function(m) { return map[m]; });
                }
    
                Array.prototype.forEach.call(SECTION_FORM , function (section) {
    
                    //Get section number for each section.
                    let sectionNumber = section.id.split('_');
    
                    // Get section name and sanitize it.
                    let sectionName = section.value;
                    let cleanSectionName = escapeHtml(sectionName);
    
                    // Get activities group for that section number.
                    let activityContainer = document.getElementById('activityContainer_' + sectionNumber[1]);
    
                    // Get activities subgroup.
                    let activityForm = activityContainer.querySelectorAll('.form-group');
    
    
                    let sectionContent = [];
    
                    // For each subgroup get activity type and title.
                    Array.prototype.forEach.call(activityForm , function (activity) {
    
                        // Get activity type and name and sanitize them.
                        let activityType = activity.querySelector('.activity-select').value;
                        let cleanActivityType = escapeHtml(activityType);
    
                        let activityName = activity.querySelector('.activity-form').value;
                        let cleanActivityName = escapeHtml(activityName);
    
                        let activityArray = [
                            cleanActivityType,
                            cleanActivityName,
                        ]
                        sectionContent.push(activityArray);
                    
                    });
                    let sectionArray = [cleanSectionName, sectionContent];
                    courseStructure.push(sectionArray);
                });
                return courseStructure;
            }       
    
            /**
             * Generate course function.
             * 
             * Add listener to prepare and submit data with course_form.php.
             */
            function generateCourseStructure(){
    
                const SAVE_BUTTON = document.getElementById('savebutton');
        
                SAVE_BUTTON.addEventListener('click', function (event) {
    
                    // Get all values from fields, sanitize and order them.
                    let courseStructure = getFormData();
    
                    // Convert to json for passing them in form.
                    let courstructurejson = JSON.stringify(courseStructure);
                    let jsonContainer = document.getElementById('coursejsoncontainer');
                    jsonContainer.value = courstructurejson;
    
                    // Activate check value for inserting in DB.
                    const FORM_VALID = document.getElementById('formvalid');
                    FORM_VALID.value = 'true';
    
                    // Submit form with new data.
                    const SUBMIT_FORM = document.getElementById('coursestructureform');
                    SUBMIT_FORM.submit();
                    MODAL.show();
                    modalGearRotate();
                })
            }
    
            /**
             * Prepare listener to generate action.
             */
            // function generate_full_course(){
    
            //     let generateButton = document.getElementById('generatebutton');
        
            //     generateButton.addEventListener('click', function (event) {
    
            //         // Get all values from fields, sanitize and order them.
            //         let courseStructure = getFormData();
    
            //         // Convert to json for passing them in form.
            //         let courstructurejson = JSON.stringify(courseStructure);
            //         let jsonContainer = document.getElementById('coursejsoncontainer');
            //         jsonContainer.value = courstructurejson;
    
            //         // Set the generate option to true.
            //         let generatecourse = document.getElementById('generatecourse');
            //         generatecourse.value = 'true';
    
            //         // Submit form with new data.
            //         let SUBMIT_FORM = document.getElementById('coursestructureform');
            //         SUBMIT_FORM.submit();
            //     })
            // }
    
            /**
             * Display Form on load Function.
             */
            return new Promise((resolve, reject) => {
                
                // Check if json response exists .
                if (typeof ANSWER_JS !== undefined) {
                    resolve(ANSWER_JS);
                } else {
                    reject('Json is undefined');
                }
            })
            .then((answerJs) => {
                // Transform JSON to iterable array.
                
                let answer = JSON.parse(answerJs);
                
                return answer;
            })
            .then((answer) => {
                
                /**
                 * Section creation from curl response.
                 */
                let answerSize = Object.keys(answer).length;
                
                for (let sectionNumber = 1; sectionNumber <= answerSize; sectionNumber ++) {
    
                    let sectionIndex = sectionNumber - 1;
                    
                    let sectionName = answer[sectionIndex][0];
    
                    let sectionTotalActivities = answer[sectionIndex][1].length;
                    
                    let formData = {
                        id: 'form_id',
                        groupname: 'sectiongroup_' + sectionNumber,
                        label: 'Section ' + sectionNumber,
                        element: {
                            elements: [
                                {
                                    input: true,
                                    name: "sectiontitle_" + sectionNumber,
                                    id: "sectiontitle_" + sectionNumber,
                                    value: sectionName,                               
                                    type: 'text',
                                    attributes: '',
                                    frozen: false,
                                    hardfrozen: false,
                                    error: null
                                },
                                {
                                    div: true,
                                    section: sectionNumber,
                                },
                                {
                                    button: true,
                                    name: 'addactivitybutton',
                                    id: sectionNumber + '_' + sectionTotalActivities,
                                    value: moduleAddButtonName,
                                    attributes: '',
                                    frozen: false,
                                    error: null
                                },
                                {
                                    removebutton: true,
                                    id: sectionNumber,
                                    value: sectionDeleteButtonName,
                                    attributes: '',
                                    frozen: false,
                                    error: null
                                },
                            ]
                        }
                    };
                    
                    let sectionContainer = document.getElementById('sectionContainer').id;
    
                    templates.render('local_courseai_elt/sectiongroup', formData).done(function(html, js) {
                        $(`#${sectionContainer}`).append(html);
                        templates.runTemplateJS(js);
                        
                        /**
                         * Activity generation.
                        */
                       let activities = answer[sectionIndex][1];
                       for (let activityNumber = 1; activityNumber <= activities.length; activityNumber ++) {
                           
                            let activityIndex = activityNumber - 1;
                            let activity = activities[activityIndex];
    
                            let activityTitle = activity[0]
                            let activityType = activity[1];
    
                            let activityName = 'subsection_' + sectionNumber + '_' + activityNumber;
                            let activityTypeName = 'activity_' + sectionNumber + '_' + activityNumber;
                            let fullSubSectionName = 'fullsubsection_' + sectionNumber + '_' + activityNumber;
    
                            function setOptionSelected(activityType) {
                                return [
                                    {
                                        text: moduleNameLabel,
                                        value: 'label',
                                        selected: activityType === 'label',
                                        disabled: false,
                                        optionattributes: ''
                                    },
                                    {
                                        text: moduleNamePage,
                                        value: 'page',
                                        selected: activityType === 'page',
                                        disabled: false,
                                        optionattributes: ''
                                    },
                                    {
                                        text: moduleNameGlossary,
                                        value: 'glossary',
                                        selected: activityType === 'glossary',
                                        disabled: false,
                                        optionattributes: ''
                                    },
                                    {
                                        text: moduleNameQuiz,
                                        value: 'quiz',
                                        selected: activityType === 'quiz',
                                        disabled: false,
                                        optionattributes: ''
                                    },
                                    {
                                        text: moduleNameForum,
                                        value: 'forum',
                                        selected: activityType === 'forum',
                                        disabled: false,
                                        optionattributes: ''
                                    },
                                    {
                                        text: moduleNameUrl,
                                        value: 'url',
                                        selected: activityType === 'url',
                                        disabled: false,
                                        optionattributes: ''
                                    }
                                ];
                            }
                            
                            // Données pour le template activitygroup.
                            let formData = {
                                element: {
                                    id: 'form_id',
                                    groupname: fullSubSectionName,
                                    label: fullSubSectionName,
                                    attributes: ["w-100"],
                                    elements: [
                                        {
                                            select: true,
                                            name: activityTypeName,
                                            id: activityTypeName,
                                            multiple: false,
                                            size: null,
                                            frozen: false,
                                            hardfrozen: false,
                                            error: null,
                                            attributes: '',
                                            options: setOptionSelected(activityType)
                                        },
                                        {
                                            input: true,
                                            name: activityName,
                                            id: activityName,
                                            value: activityTitle,
                                            size: '50',
                                            type: 'text',
                                            attributes: '',
                                            frozen: false,
                                            hardfrozen: false,
                                            error: null
                                        },
                                        {
                                            button: true,
                                            name: 'removebutton',
                                            id: 'removeActBtn_' + sectionNumber + '_' + activityNumber,
                                            value: '<i class="icon fa fa-trash fa-fw icon-class" aria-hidden="true"></i>',
                                            attributes: 'class="remove-button btn btn_primary"',
                                            frozen: false,
                                            error: null
                                        }
                                    ]
                                }
                            };
                            
                            // Set container DOM location.
                            let containerId = 'activityContainer_' + sectionNumber;
                            
                            // Load and display template before the add activity button.
                            templates.render('local_courseai_elt/activitygroup', formData).done(function(html, js) {
        
                                // Add HTML to container.
                                $(`#${containerId}`).append(html);
                                // Execute JS.
                                templates.runTemplateJS(js);
                                    
                                // Error response for activity creation
                                }).fail(function(err) {
                                    console.error(err);
                            });
                        }
                    // Error response for section creation.
                    }).fail(function(err) {
                        console.error(err);
                    })
                };
            })
            .then (() => {
    
                /**
                 * Add Mutation Observer to follow DOM movements.
                 * 
                 * https://developer.mozilla.org/en-US/docs/Web/API/MutationObserver
                 */
    
                // Select the DOM node that will be observed for mutations.
                const SECTION_CONTAINER = document.getElementById('sectionContainer');
                // Options for the observer (which mutations to observe).
                const CONFIG = { attributes: true, childList: true, subtree: true };
                // Callback function to execute when mutations are observed
                const callback = (mutationList, observer) => {
                    for (const mutation of mutationList) {
                        
                        // Initialize or re-assign all listeners.
                        removeActivityGroup();
                        removeSectionGroup();
                        addActivityGroup();
                        
                    }       
                };
                // Initialize listener for submit buttons save and generate.
                generateCourseStructure();
                // generate_full_course();
    
                // Create an observer instance linked to the callback function
                const OBSERVER = new MutationObserver(callback);
    
                // Start observing the target node for configured mutations
                OBSERVER.observe(SECTION_CONTAINER, CONFIG);
    
            })
            .catch((error) => {
                console.error('Error executing async; ', error);
            })
    
        };
        // Return the different function ready to be executed.
        return {
            init: init
        };
    });
    