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
 * Plugin settings. Api key and model are defined here.
 *
 * @package     local_courseai_elt
 * @copyright   2024 E-Learning Touch <https://www.elearningtouch.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


 defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $ADMIN->add(
        'localplugins',
        new admin_category('local_courseai_elt_settings',
        new lang_string('pluginname', 'local_courseai_elt')));

    $settings = new admin_settingpage(
        'local_courseai_elt',
        new lang_string('pluginname', 'local_courseai_elt'));

    if ($ADMIN->fulltree) {

        // API key.
        $settings->add(new admin_setting_configtext(
            'local_courseai_elt/apikey',
            new lang_string('apikey', 'local_courseai_elt'),
            new lang_string('apikey_desc', 'local_courseai_elt'),
            ''
        ));

        // API model choices.
        $options = [
            'mistralai' => new lang_string('mistralai', 'local_courseai_elt'),
            'openai' => new lang_string('openai', 'local_courseai_elt'),
        ];
        $settings->add(new admin_setting_configselect(
            'local_courseai_elt/processingai',
            new lang_string('aitype', 'local_courseai_elt'),
            new lang_string('aitype_desc', 'local_courseai_elt'),
            '',
            $options
        ));
    }

    $ADMIN->add('localplugins', $settings);
}
