Feature: Test every supported breakpoint

  Scenario: Test alert breakpoint
    Then an alert breakpoint is triggered

  Scenario: Test alert breakpoint with custom message
    Then an alert breakpoint is triggered with message "Some test message."

  Scenario: Test console breakpoint
    Then a console breakpoint is triggered

  Scenario: Test console breakpoint with custom message
    Then a console breakpoint is triggered with message "Some test message."

  Scenario: Test popup breakpoint
    Then a popup breakpoint is triggered with the following content:
      """
      <h1>Hello world</h1>
      <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.</p>
      <nav>
        <ul>
          <li><a href="#nowhere" title="Lorum ipsum dolor sit amet">Lorem</a></li>
          <li><a href="#nowhere" title="Aliquam tincidunt mauris eu risus">Aliquam</a></li>
          <li><a href="#nowhere" title="Morbi in sem quis dui placerat ornare">Morbi</a></li>
          <li><a href="#nowhere" title="Praesent dapibus, neque id cursus faucibus">Praesent</a></li>
          <li><a href="#nowhere" title="Pellentesque fermentum dolor">Pellentesque</a></li>
        </ul>
      </nav>
      """

  Scenario: Test popup breakpoint with custom size
    Then a 300x200 popup breakpoint is triggered with the following content:
      """
      <h1>Hello world</h1>
      """

  Scenario: Test xdebug breakpoint
    Then an xdebug breakpoint is triggered
