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

class condition extends \core_availability\condition
{
    protected $userid;
    
    public function __construct($structure)
    {
        if (isset($structure->userid)) {
            $this->userid = $structure->userid;
        }
    }

    public function save()
    {
        return (object)array('type' => 'user', 'userid' => $this->userid);
    }

    public function is_available($not, \core_availability\info $info, $grabthelot, $userid)
    {
        return $not ^ $userid == $this->userid;
    }

    public function get_description($full, $not, \core_availability\info $info)
    {
        $user = \core_user::get_user($this->userid, 'firstname,lastname');
        if (!$user) {
            return get_string('requires_unknown_user', 'availability_user');
        } else {
            return get_string('requires_user', 'availability_user', $user->lastname.', '.$user->firstname);
        }
    }

    protected function get_debug_string()
    {
        return $this->userid;
    }
}
