Feature: Book a class

  @javascript
  Scenario: Book cycling for next day
    Given I am on the members page
    And I complete email field
    And I complete password field
    And I submit login form
    And I go to the schedule page
    And I select the preferred club
    And I select the class type
    And I book tomorrow class with preferred instructor