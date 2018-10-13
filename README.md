# behat-breakpoint
✋ Provides various ways to break/block scenarios.

<!-- TODO add badges here -->

<!-- TODO add screen shot of each breakpoint type -->

## Installation

1. Install with [Composer](https://getcomposer.org/) in your Behat project:
   ```sh
   composer require uuf6429/behat-breakpoint
   ```
2. (Optionally) install the context (in your `behat.yml`) so you can use it in your tests:
   ```yaml
   default:
     extensions:
       Behat\MinkExtension:
   # ...
     suites:
       default:
         paths:
           features: "%paths.base%/features/"
         contexts:
         - Behat\MinkExtension\Context\MinkContext
   # ...
         - uuf6429\BehatBreakpoint\Context\BreakpointContext  #  <-- just add this line
   # ...
   ```

## Requirements

You can look at [composer.json](composer.json) for the specifics, but PHP 5.7+ and Behat 3.3+ are always required.
Additionally, each type of breakpoint has specific requirements:
- `AlertBreakpoint`: Requires [instaclick/php-webdriver](https://github.com/instaclick/php-webdriver) (which is a part of [behat/mink-selenium2-driver](https://github.com/minkphp/MinkSelenium2Driver)).
- `ConsoleBreakpoint`: None.
- `PopupBreakpoint`: Requires [behat/mink](https://github.com/minkphp/Mink) (which is a part of [behat/mink-extension](https://github.com/Behat/MinkExtension)).
- `XdebugBreakpoint`: Requires [ext-xdebug](https://xdebug.org/download.php) to be installed and enabled.

None of the above packages are installed automatically; since someone may want to use the Xdebug extension without needing Mink (and vice-versa).

## Usage

<!-- src/docgen.php -->

### In Code

Construct the desired breakpoint from the ones listed below and `trigger()` it.

- **[AlertBreakpoint](src/Breakpoint/AlertBreakpoint)** - *Shows a javascript alert in the specified browser session and waits until it is closed.*
  ```php
  new \uuf6429\BehatBreakpoint\Breakpoint\AlertBreakpoint(
      $session,    //
      $message = 'Breakpoint reached! Press [OK] to continue.'    // (Optional) A message to show to the operator.
  )
  ```
- **[ConsoleBreakpoint](src/Breakpoint/ConsoleBreakpoint)** - *Displays a message in the current terminal and waits until [enter] is pressed.*
  ```php
  new \uuf6429\BehatBreakpoint\Breakpoint\ConsoleBreakpoint(
      $message = 'Breakpoint reached! Press [Enter] to continue.',    // (Optional) A message to show to the operator.
      $output = NULL,    // (Optional) Output handle (defaults to PHP's STDOUT)
      $input = NULL    // (Optional) Input handle (defaults to PHP's STDIN)
  )
  ```
- **[PopupBreakpoint](src/Breakpoint/PopupBreakpoint)** - *Displays a new window with some HTML and waits until it is closed by the user.*
  ```php
  new \uuf6429\BehatBreakpoint\Breakpoint\PopupBreakpoint(
      $session,    //
      $popupHtml,    //
      $popupWidth = 500,    // (Optional)
      $popupHeight = 300,    // (Optional)
      $popupIsScrollable = false,    // (Optional)
      $popupIsResizeable = false    // (Optional)
  )
  ```
- **[XdebugBreakpoint](src/Breakpoint/XdebugBreakpoint)** - *Pauses execution until a connected xdebug client resumes execution.*
  ```php
  new \uuf6429\BehatBreakpoint\Breakpoint\XdebugBreakpoint()
  ```

### In Gherkin

First add the context to your behat project config (`behat.yml`) and then use any of the following steps in your feature files:

<!-- /src/docgen.php -->
