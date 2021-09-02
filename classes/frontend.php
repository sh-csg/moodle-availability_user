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
 * Plugin version and other meta-data are defined here.
 *
 * @package     availability_user
 * @copyright   2021 Stefan Hanauska <stefan.hanauska@altmuehlnet.de>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace availability_user;

defined('MOODLE_INTERNAL') || die();

class frontend extends \core_availability\frontend {

    protected function get_javascript_strings() {
        return ['title', 'description', 'missing_user'];
    }

    protected function get_javascript_init_params($course, \cm_info $cm = null, \section_info $section = null) {
        $context = \context_course::instance($course->id);
        $participants = get_enrolled_users($context);
        $participant_list = array();
        foreach($participants as $p) {
            array_push($participant_list, array('firstname' => $p->firstname, 'lastname' => $p->lastname, 'id' => $p->id));
        }
        return array($participant_list);
    }

    protected function allow_add($course, \cm_info $cm = null, \section_info $section = null) {
        // only allowed if there are enrolled users in the course
        return(get_enrolled_users(\context_course::instance($course->id))>0);
    }
}