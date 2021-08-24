Feature: Big PyString
  Scenario:
    Then it should fail with:
      """

      # language: ru

      UUUUUU

      2 scenarios (2 undefined)
      6 steps (6 undefined)

      You can implement step definitions for undefined steps with these snippets:

      $steps->Given('/^I have entered (\d+)$/', function($world, $arg1) {
          throw new \Everzet\Behat\Exception\Pending();
      });

      $steps->Then('/^I must have (\d+)$/', function($world, $arg1) {
          throw new \Everzet\Behat\Exception\Pending();
      });

      $steps->Then('/^String must be \'([^\']*)\'$/', function($world, $arg1) {
          throw new \Everzet\Behat\Exception\Pending();
      });
      """
