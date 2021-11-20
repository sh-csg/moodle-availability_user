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
        $this->user3 = $this->getDataGenerator()->create_user(
            array(
            'email' => 'user3@example.com',
            'username' => 'user3')
        );
        $this->user4 = $this->getDataGenerator()->create_user(
            array(
            'email' => 'user4@example.com',
            'username' => 'user4')
        );
        $oldstructure = new stdClass;
        $oldstructure->userid = $this->user1->id;
        $this->cond = new condition($oldstructure);

        $newstructure = new stdClass;
        $newstructure->userids = [$this->user1->id];
        $this->newcond = new condition($newstructure);

        $multiplestructure = new stdClass;
        $multiplestructure->userids = [$this->user1->id, $this->user2->id, $this->user3->id];
        $this->multiplecond = new condition($multiplestructure);
    }

    /**
     * Check whether the item is available for the right user
     * This test uses the old structure (single userid)
     *
     * @return void
     */
    public function test_right_user_old_structure() {
        global $USER;
        $this->setUser($this->user1);
        $this->assertTrue($this->cond->is_available(false, $this->info, true, $USER->id));
    }

    /**
     * Check whether the item is not available for the right user if using "not" operator
     * This test uses the old structure (single userid)
     *
     * @return void
     */
    public function test_right_user_not_old_structure() {
        global $USER;
        $this->setUser($this->user1);
        $this->assertFalse($this->cond->is_available(true, $this->info, true, $USER->id));
    }

    /**
     * Check whether the item is not available for the wrong user
     * This test uses the old structure (single userid)
     *
     * @return void
     */
    public function test_wrong_user_old_structure() {
        global $USER;
        $this->setUser($this->user2);
        $this->assertFalse($this->cond->is_available(false, $this->info, true, $USER->id));
        $this->assertTrue($this->cond->is_available(true, $this->info, true, $USER->id));
    }

    /**
     * Check whether the item is available for the wrong user if using "not" operator
     * This test uses the old structure (single userid)
     *
     * @return void
     */
    public function test_wrong_user_not_old_structure() {
        global $USER;
        $this->setUser($this->user2);
        $this->assertTrue($this->cond->is_available(true, $this->info, true, $USER->id));
    }

    /**
     * Check whether the item is available for the right user
     * This test uses the new structure (array of userids)
     *
     * @return void
     */
    public function test_right_user_new_structure() {
        global $USER;
        $this->setUser($this->user1);
        $this->assertTrue($this->newcond->is_available(false, $this->info, true, $USER->id));
    }

    /**
     * Check whether the item is not available for the right user if using "not" operator
     * This test uses the new structure (array of userids)
     *
     * @return void
     */
    public function test_right_user_not_new_structure() {
        global $USER;
        $this->setUser($this->user1);
        $this->assertFalse($this->newcond->is_available(true, $this->info, true, $USER->id));
    }

    /**
     * Check whether the item is not available for the wrong user
     * This test uses the new structure (array of userids)
     *
     * @return void
     */
    public function test_wrong_user_new_structure() {
        global $USER;
        $this->setUser($this->user2);
        $this->assertFalse($this->newcond->is_available(false, $this->info, true, $USER->id));
        $this->assertTrue($this->newcond->is_available(true, $this->info, true, $USER->id));
    }

    /**
     * Check whether the item is available for the wrong user if using "not" operator
     * This test uses the new structure (array of userids)
     *
     * @return void
     */
    public function test_wrong_user_not_new_structure() {
        global $USER;
        $this->setUser($this->user2);
        $this->assertTrue($this->newcond->is_available(true, $this->info, true, $USER->id));
    }

    /**
     * Check whether the item is available for the right user
     * This test uses a condition for multiple users
     *
     * @return void
     */
    public function test_right_users_multiple() {
        global $USER;
        $this->setUser($this->user1);
        $this->assertTrue($this->multiplecond->is_available(false, $this->info, true, $USER->id));
        $this->setUser($this->user2);
        $this->assertTrue($this->multiplecond->is_available(false, $this->info, true, $USER->id));
        $this->setUser($this->user3);
        $this->assertTrue($this->multiplecond->is_available(false, $this->info, true, $USER->id));
    }

    /**
     * Check whether the item is available for the right user if using "not" operator
     * This test uses a condition for multiple users
     *
     * @return void
     */
    public function test_right_user_not_multiple() {
        global $USER;
        $this->setUser($this->user1);
        $this->assertFalse($this->multiplecond->is_available(true, $this->info, true, $USER->id));
        $this->setUser($this->user2);
        $this->assertFalse($this->multiplecond->is_available(true, $this->info, true, $USER->id));
        $this->setUser($this->user3);
        $this->assertFalse($this->multiplecond->is_available(true, $this->info, true, $USER->id));
    }

    /**
     * Check whether the item is not available for the wrong user
     * This test uses a condition for multiple users
     *
     * @return void
     */
    public function test_wrong_user_multiple() {
        global $USER;
        $this->setUser($this->user4);
        $this->assertFalse($this->multiplecond->is_available(false, $this->info, true, $USER->id));
    }

    /**
     * Check whether the item is available for the wrong user if using "not" operator
     * This test uses a condition for multiple users
     *
     * @return void
     */
    public function test_wrong_user_not_multiple() {
        global $USER;
        $this->setUser($this->user4);
        $this->assertTrue($this->multiplecond->is_available(true, $this->info, true, $USER->id));
    }

}
