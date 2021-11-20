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

/**
 * Testcase for availability_user
 */
class availability_user_condition_testcase extends advanced_testcase {

    /**
     * Load necessary libs
     */
    public static function setupBeforeClass(): void {
        global $CFG;
        require_once($CFG->dirroot . '/availability/tests/fixtures/mock_info.php');
    }

    /**
     * Prepare testing
     */
    public function setUp(): void {
        global $DB, $CFG;
        $this->setAdminUser();
        $this->info = new \core_availability\mock_info();
        $this->resetAfterTest();
        $this->user1 = $this->getDataGenerator()->create_user(
            array(
            'email' => 'user1@example.com',
            'username' => 'user1')
        );
        $this->user2 = $this->getDataGenerator()->create_user(
            array(
            'email' => 'user2@example.com',
            'username' => 'user2')
        );
        $structure = new stdClass;
        $structure->userid = $this->user1->id;
        $this->cond = new condition($structure);
    }

    /**
     * Check whether the item is available for the right user
     *
     * @return void
     */
    public function test_right_user() {
        global $USER;
        $this->setUser($this->user1);
        $this->assertTrue($this->cond->is_available(false, $this->info, true, $USER->id));
    }

    /**
     * Check whether the item is not available for the right user if using "not" operator
     *
     * @return void
     */
    public function test_right_user_not() {
        global $USER;
        $this->setUser($this->user1);
        $this->assertFalse($this->cond->is_available(true, $this->info, true, $USER->id));
    }

    /**
     * Check whether the item is not available for the wrong user
     *
     * @return void
     */
    public function test_wrong_user() {
        global $USER;
        $this->setUser($this->user2);
        $this->assertFalse($this->cond->is_available(false, $this->info, true, $USER->id));
        $this->assertTrue($this->cond->is_available(true, $this->info, true, $USER->id));
    }

    /**
     * Check whether the item is not available for the wrong user if using "not" operator
     *
     * @return void
     */
    public function test_wrong_user_not() {
        global $USER;
        $this->setUser($this->user2);
        $this->assertTrue($this->cond->is_available(true, $this->info, true, $USER->id));
    }
}
