<?php
require_once('AutoLoader.php');
// Register the directory to your include files
AutoLoader::registerDirectory(dirname(__FILE__));

$configuration = new SeleniumConfiguration();
$configuration->defaultBrowser = "firefox";
$configuration->durationDelay = 2;
$configuration->durationWait = 25000;
$configuration->durationTimeout = 3000;
$configuration->captureScreenshotOnFailure = false;
$configuration->screenShotPath = __DIR__ . '/screenshots';

Selenium::$configuration = $configuration;