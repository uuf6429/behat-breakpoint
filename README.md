# behat-breakpoint

[![Build Status](https://travis-ci.org/uuf6429/behat-breakpoint.svg?branch=master)](https://travis-ci.org/uuf6429/behat-breakpoint)
[![Minimum PHP Version](https://img.shields.io/badge/php-^5.6%20||%20^7.0-8892BF.svg)](https://php.net/)
[![Minimum Behat Version](https://img.shields.io/badge/behat-^3.0-0B0B0A.svg)](http://behat.org/)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/uuf6429/behat-breakpoint/master/LICENSE)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=BehatBreakpoint&metric=coverage)](https://sonarcloud.io/component_measures?id=BehatBreakpoint&metric=coverage)
[![Reliability](https://sonarcloud.io/api/project_badges/measure?project=BehatBreakpoint&metric=reliability_rating)](https://sonarcloud.io/dashboard?id=BehatBreakpoint)
[![Packagist](https://img.shields.io/packagist/v/uuf6429/behat-breakpoint.svg)](https://packagist.org/packages/uuf6429/behat-breakpoint)

✋ Provides various ways to break/block scenarios in Behat.

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
         - uuf6429\BehatBreakpoint\Context           #  <-- just add this line
   # ...
   ```

## Requirements

You can look at [composer.json](composer.json) for the specifics, but PHP 5.6+ and Behat 3.0+ are always required.
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
      \Session $session,                                                 // The WebDriver session to work with.
      string $message = 'Breakpoint reached! Press [OK] to continue.'    // (Optional) A message to show to the operator.
  )
  ```
- **[ConsoleBreakpoint](src/Breakpoint/ConsoleBreakpoint)** - *Displays a message in the current terminal and waits until [enter] is pressed.*
  ```php
  new \uuf6429\BehatBreakpoint\Breakpoint\ConsoleBreakpoint(
      string $message = NULL,          // (Optional) A message to show to the operator.
      null|resource $output = NULL,    // (Optional) Output handle (defaults to PHP's STDOUT)
      null|resource $input = NULL      // (Optional) Input handle (defaults to PHP's STDIN)
  )
  ```
- **[PopupBreakpoint](src/Breakpoint/PopupBreakpoint)** - *Displays a new window with some HTML and waits until it is closed by the user.*
  ```php
  new \uuf6429\BehatBreakpoint\Breakpoint\PopupBreakpoint(
      \Session $session,                  // The Mink session to work with. It must support javascript.
      string $popupHtml,                  // The HTML of the popup page *body*.
      int $popupWidth = 500,              // (Optional) The popup's default width.
      int $popupHeight = 300,             // (Optional) The popup's default height.
      bool $popupIsScrollable = false,    // (Optional) Enables scrollbars (and scrolling) within the popup.
      bool $popupIsResizeable = false     // (Optional) Allows the popup to be resizeable.
  )
  ```
- **[XdebugBreakpoint](src/Breakpoint/XdebugBreakpoint)** - *Pauses execution until a connected xdebug client resumes execution.*
  ```php
  new \uuf6429\BehatBreakpoint\Breakpoint\XdebugBreakpoint()
  ```

### In Gherkin

First add the context to your behat project config (`behat.yml`) and then use any of the following steps in your feature files:

<!-- /src/docgen.php -->
