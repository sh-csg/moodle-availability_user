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
 * Condition to restrict access by user
 *
 * @package     availability_user
 * @copyright   2021 Stefan Hanauska <stefan.hanauska@altmuehlnet.de>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace availability_user;

defined('MOODLE_INTERNAL') || die();

/**
 * Condition to restrict access by user
 */
class condition extends \core_availability\condition {
    protected $userid;

    /**
     * Constructor
     *
     * @param  mixed $structure Data structure from JSON decode
     * @return void
     */
    public function __construct($structure) {
        if (isset($structure->userid)) {
            $this->userid = $structure->userid;
        }
    }

    /**
     * Saves tree data back to a structure object.
     *
     * @return stdClass Structure object (ready to be made into JSON format)
     */
    public function save() {
        return (object)['type' => 'user', 'userid' => $this->userid];
    }

    /**
     * Determines whether this item is availabile.
     *
     * @param  mixed $not   Set true if we are inverting the condition
     * @param  mixed $info  Item we're checking
     * @param  mixed $grabthelot    Performance hint: if true, caches information required for all course-modules, to make the front page and similar pages work more quickly (works only for current user)
     * @param  mixed $userid    User ID to check availability for
     * @return bool true if available
     */
    public function is_available($not, \core_availability\info $info, $grabthelot, $userid) {
        return $not ^ $userid == $this->userid;
    }

    /**
     * Obtains a string describing this restriction (whether or not it actually applies).
     *
     * @param  mixed $full  Set true if this is the 'full information' view
     * @param  mixed $not   Set true if we are inverting the condition
     * @param  mixed $info  Item we're checking
     * @return string Information string (for admin) about all restrictions on this item
     */
    public function get_description($full, $not, \core_availability\info $info) {
        $user = \core_user::get_user($this->userid, 'firstname,lastname');
        if (!$user) {
            return get_string('requires_unknown_user', 'availability_user');
        } else {
            return get_string('requires_user', 'availability_user', $user->lastname . ', ' . $user->firstname);
        }
    }

    /**
     * Obtains a representation of the options of this condition as a string, for debugging.
     *
     * @return string Id of requested user
     */
    protected function get_debug_string() {
        return $this->userid;
    }
}
