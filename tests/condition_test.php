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
 * Test for restriction by user
 *
 * @package     availability_user
 * @copyright   2021 Stefan Hanauska <stefan.hanauska@altmuehlnet.de>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

use availability_user\condition;

class availability_user_condition_testcase extends advanced_testcase {
    public function setUp(): void {
        global $DB, $CFG;
        $this->setAdminUser();
        $info = new \core_availability\mock_info();
        $this->resetAfterTest();
        $user1 = $this->getDataGenerator()->create_user(array(
            'email' => 'user1@example.com',
            'username' => 'user1')
        );
        $user2 = $this->getDataGenerator()->create_user(array(
            'email' => 'user2@example.com',
            'username' => 'user2')
        );
        $structure = new object();
        $structure->userid = $user1->id;
        $cond = new condition($structure);
        $this->assertTrue($cond->is_available(false, $info, true, $user1->id));
        $this->assertFalse($cond->is_available(false, $info, true, $user2->id));
        $this->assertFalse($cond->is_available(true, $info, true, $user1->id));
        $this->assertTrue($cond->is_available(true, $info, true, $user2->id));
    }
}
