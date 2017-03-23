<?php

if (!defined ('TYPO3_MODE')) {
    die ('Access denied.');
}

$config = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['rollbar']['rollbar_config'] ?: array();
$config['root'] = $config['root'] ?: PATH_site;

Rollbar::init(
    $config,
    /**
     * Note these are configurable, but handled in ErrorHandler and ExceptionHandler
     */
    false,
    false,

    /**
     * This defaults to true
     */
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['rollbar']['report_fatal_errors'] === false ? false : true

);

$GLOBALS['TYPO3_CONF_VARS']['SYS']['errorHandler'] = 'CIC\Rollbar\Error\ErrorHandler';
$GLOBALS['TYPO3_CONF_VARS']['SYS']['debugExceptionHandler'] = 'CIC\Rollbar\Error\ExceptionHandler';
$GLOBALS['TYPO3_CONF_VARS']['SYS']['productionExceptionHandler'] = 'CIC\Rollbar\Error\ProductionExceptionHandler';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['errors']['exceptionHandler'] = $GLOBALS['TYPO3_CONF_VARS']['SYS']['productionExceptionHandler'];
