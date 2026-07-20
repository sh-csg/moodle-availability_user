@availability @availability_user
Feature: availability_user
  In order to control student access to activities
  As a teacher
  I need to set user conditions which prevent student access

  Background:
    Given the following "courses" exist:
      | fullname | shortname | format | numsections |
      | Course 1 | C1        | topics | 3           |
    And the following "users" exist:
      | username | firstname | lastname |
      | teacher1 | Teacher   | One      |
      | student1 | Alice     | Smith    |
      | student2 | Bob       | Jones    |
      | student3 | Carol     | White    |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
      | student1 | C1     | student        |
      | student2 | C1     | student        |
      | student3 | C1     | student        |
    And the following "activities" exist:
      | activity | course | name |
      | page     | C1     | P1   |

  @javascript
  Scenario: Restricting access to a single user works correctly
    Given I am on the "P1" "page activity editing" page logged in as "teacher1"
    And I expand all fieldsets
    And I click on "Add restriction..." "button"
    And I click on "#availability_addrestriction_user" "css_element"
    And I set the field "availability_user_userids" to "Alice Smith"
    And I click on ".availability-item .availability-eye img" "css_element"
    And I click on "Save and return to course" "button"

    # student1 (Alice) should see P1, others should not.
    When I am on the "Course 1" "course" page logged in as "student1"
    Then I should see "P1" in the "region-main" "region"

    When I am on the "Course 1" "course" page logged in as "student2"
    Then I should not see "P1" in the "region-main" "region"

  @javascript
  Scenario: Selecting all users via Ctrl+A saves all of them correctly
    # Regression test: Ctrl+A on the multi-select was broken because the plugin
    # listened to 'click' (never fired for keyboard selection) and fillValue used
    # a broken YUI chain get('options').get('_nodes') returning array-of-arrays.
    Given I am on the "P1" "page activity editing" page logged in as "teacher1"
    And I expand all fieldsets
    And I click on "Add restriction..." "button"
    And I click on "#availability_addrestriction_user" "css_element"
    # Click the first option to give the select keyboard focus, then Ctrl+A.
    And I click on "Alice Smith" "option" in the "#availability_user_userids" "css_element"
    And I press the ctrl a key
    And I click on ".availability-item .availability-eye img" "css_element"
    And I click on "Save and return to course" "button"

    # All three students must be allowed — none should be excluded.
    When I am on the "Course 1" "course" page logged in as "student1"
    Then I should see "P1" in the "region-main" "region"

    When I am on the "Course 1" "course" page logged in as "student2"
    Then I should see "P1" in the "region-main" "region"

    When I am on the "Course 1" "course" page logged in as "student3"
    Then I should see "P1" in the "region-main" "region"

    # Re-open and verify all three names are still selected (save/load round-trip).
    When I am on the "P1" "page activity editing" page logged in as "teacher1"
    And I expand all fieldsets
    Then the field "availability_user_userids" matches value "Bob Jones, Teacher One, Alice Smith, Carol White"

  @javascript
  Scenario: Selecting a subset of users saves the correct subset
    Given I am on the "P1" "page activity editing" page logged in as "teacher1"
    And I expand all fieldsets
    And I click on "Add restriction..." "button"
    And I click on "#availability_addrestriction_user" "css_element"
    And I set the field "availability_user_userids" to "Alice Smith, Bob Jones"
    And I click on ".availability-item .availability-eye img" "css_element"
    And I click on "Save and return to course" "button"

    # Alice and Bob can see P1; Carol cannot.
    When I am on the "Course 1" "course" page logged in as "student1"
    Then I should see "P1" in the "region-main" "region"

    When I am on the "Course 1" "course" page logged in as "student2"
    Then I should see "P1" in the "region-main" "region"

    When I am on the "Course 1" "course" page logged in as "student3"
    Then I should not see "P1" in the "region-main" "region"
