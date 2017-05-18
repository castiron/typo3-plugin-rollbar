<?php

if (!defined ('TYPO3_MODE')) {
    die ('Access denied.');
}

/**
 * NB: This file is not used for registering Rollbar features, because it is loaded too late. Instead, check the
 * README of this plugin for how to activate Rollbar error handling in your project. You'll want to run
 * CIC\Rollbar\Utility\Initializer::initErrorHandling() after you've set your rollbar config options in
 * [PATH_site]/localconf/AdditionalConfiguration.php
 */
