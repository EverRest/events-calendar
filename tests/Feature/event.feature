Feature: EventController Actions

    Background:
        Given some events exist in the database

    Scenario: User requests the list of events
        When a user requests the list of events
        Then the response should have a status code of 200
        And the response should be in the expected format

    Scenario: User requests a specific event with correct parameter
        Given an existing event in the database
        When a user requests a specific event
        Then the response should have a status code of 200
        And the response should be in the expected format

    Scenario: User requests a non-existing event
        When a user requests a non-existing event
        Then the response should have a status code of 404

    Scenario: User requests to delete an existing event
        Given an existing event in the database
        When a user requests to delete the event
        Then the response should have a status code of 204
        And the event should be deleted from the database

    Scenario: User requests to delete a non-existing event
        When a user requests to delete a non-existing event
        Then the response should have a status code of 404
