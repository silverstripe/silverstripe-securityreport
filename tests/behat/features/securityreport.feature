Feature: Security report
  As a website user
  I want to use the security report

  Background:
    Given the "group" "EDITOR" has permissions "CMS_ACCESS_LeftAndMain" and "FILE_EDIT_ALL"

    # Login as EDITOR to create member
    And I am logged in as a member of "EDITOR" group

    # Logout and login again as ADMIN
    And I go to "/Security/login"
    And I press the "Log in as someone else" button
    And I am logged in with "ADMIN" permissions

    # Create a subsite
    And I go to "/admin/subsites"
    And I press the "Add Subsite" button
    And I fill in "Subsite Name" with "My subsite"
    And I press the "Create" button

  Scenario: Operate the security report
    
    # Show members
    When I go to "/admin/reports"
    And I follow "Users, Groups and Permissions"

    # Assert firstname, surname and email
    Then I should see "EDITOR"
    And I should see "User"
    And I should see "EDITOR@example.org"

    # Groups column
    And I should see "EDITOR (global group)"

    # Permissions column
    Then I should see "Edit any file"
    And I should see "Full administrative rights"

    # Subsites column
    And I should see "Main site, My subsite"
