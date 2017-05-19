<?php namespace CIC\Rollbar\Utility;
use Rollbar\Rollbar;

/**
 * Class Initializer
 * @package CIC\Rollbar\Utility
 */
class Initializer {
    public static function initErrorHandling() {

        /**
         * Don't do anything if this isn't turned on.
         */
        if (!static::rollbarEnabled()) {
            return;
        }

        /**
         * Add the autoloader for TYPO3 to find our custom error handling classes
         */
        static::initRollbarTypo3PluginAutoloader();

        /**
         * Register our custom error handling classes with TYPO3_CONF_VARS
         */
        static::setErrorHandlingConfig();

        /**
         * Init rollbar
         */
        static::initializeRollbar();
    }

    /**
     * We need to register a custom autoloader because the TYPO3 core doesn't initialize autoloaders until after
     * error handling is set up.
     */
    protected static function initRollbarTypo3PluginAutoloader() {
        spl_autoload_register(function ($className) {
            $prefix = 'CIC\Rollbar\Error\\';
            if (strpos($className, $prefix) === 0) {
                $fileName = str_replace('\\', '/', substr($className, (strlen($prefix)))) . '.php';
                $file = PATH_site . 'typo3conf/ext/rollbar/Classes/Error/' . $fileName;
                require $file;
            }
        });
    }

    /**
     *
     */
    protected static function setErrorHandlingConfig() {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['errorHandler'] = 'CIC\\Rollbar\\Error\\ErrorHandler';
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['debugExceptionHandler'] = 'CIC\\Rollbar\\Error\\ExceptionHandler';
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['productionExceptionHandler'] = 'CIC\\Rollbar\\Error\\ProductionExceptionHandler';
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['errors']['exceptionHandler'] = $GLOBALS['TYPO3_CONF_VARS']['SYS']['productionExceptionHandler'];
    }

    /**
     * @return array
     */
    protected static function typo3RollbarConfig() {
        $config = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['rollbar']['rollbar_config'] ?: array();
        $config['root'] = $config['root'] ?: PATH_site;
        return $config;
    }

    protected static function initializeRollbar() {
        Rollbar::init(
            static::typo3RollbarConfig(),

            /**
             * Note these are hard-coded here because they're handled explicitly in ErrorHandler and ExceptionHandler
             */
            false,
            false,

            /**
             * This defaults to true
             */
            $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['rollbar']['report_fatal_errors'] === false ? false : true
        );
    }

    /**
     * @return bool
     */
    protected static function rollbarEnabled() {
        return $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['rollbar']['rollbar_enabled'] ? true : false;
    }
}
