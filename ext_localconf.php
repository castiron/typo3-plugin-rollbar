<?php

if (!defined ('TYPO3_MODE')) {
    die ('Access denied.');
}

$config = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['rollbar']['rollbar_config'] ?: array();
$config['root'] = $config['root'] ?: PATH_site;

Rollbar::init(
    $config,
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['rollbar']['set_exception_handler'],
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['rollbar']['set_error_handler'],
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['rollbar']['report_fatal_errors'] === false ? false : true
);
