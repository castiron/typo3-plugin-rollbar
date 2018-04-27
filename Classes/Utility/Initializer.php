<?php namespace CIC\Rollbar\Utility;

use Rollbar\Rollbar;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;

/**
 * Class Initializer
 * @package CIC\Rollbar\Utility
 */
class Initializer {

    /**
     *
     */
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
     * Configure TYPO3 to use our error handlers (which will defer on to the core ones anyhow).
     */
    protected static function setErrorHandlingConfig() {
        if (static::usesLegacyExceptionHandlerInterface()) {
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['errorHandler'] = 'CIC\\Rollbar\\Error\\Legacy\\ErrorHandler';
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['debugExceptionHandler'] = 'CIC\\Rollbar\\Error\\Legacy\\ExceptionHandler';
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['productionExceptionHandler'] = 'CIC\\Rollbar\\Error\\Legacy\\ProductionExceptionHandler';
            $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['errors']['exceptionHandler'] = $GLOBALS['TYPO3_CONF_VARS']['SYS']['productionExceptionHandler'];
            return;
        }

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
        /**
         * This isn't enabled if the extension isn't loaded. We're early in the bootstrapping process.
         */
        if (!static::rollbarExtensionIsActive()) {
            return false;
        }

        /**
         * If there's no access token just don't use Rollbar
         */
        if (!$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['rollbar']['rollbar_config']['access_token']) {
            return false;
        }

        /**
         * The reporting can also be disabled by explicit flag
         */
        return $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['rollbar']['rollbar_enabled'] ? true : false;
    }

    /**
     * @return string
     */
    protected static function packageStatesPath() {
        return PATH_site . 'typo3conf/PackageStates.php';
    }

    /**
     * We have to check this manually because we're so early in the bootstrap process. Extensions have not been
     * loaded yet.
     */
    protected static function rollbarExtensionIsActive() {
        /**
         * Look at the PackageStates.php file
         */
        $packagesFile = static::packageStatesPath();
        if (!file_exists($packagesFile)) {
            return false;
        }

        /**
         * Load the config array from there
         */
        $extConfig = include($packagesFile);

        /**
         * Bail if we don't have rollbar config
         */
        $rollbarConfig = $extConfig['packages']['rollbar'];
        if (!is_array($rollbarConfig)) {
            return false;
        }

        /**
         * Inspect package states config for active; logic varies by TYPO3 version
         */
        return static::checkPackageStatesConfigForActive($rollbarConfig);
    }

    /**
     * @param $rollbarPackageConfig
     * @return bool
     */
    protected static function checkPackageStatesConfigForActive($rollbarPackageConfig)
    {
        if (!$rollbarPackageConfig) {
            return false;
        }

        /**
         * We guarded for absence, so if we're using a modern TYPO3 we are good to go
         */
        if (static::usesSimplePackageStatesFormat()) {
            return true;
        }

        /**
         * Older-school style, we have to check for 'active'
         */
        return $rollbarPackageConfig['state'] === 'active';
    }

    /**
     * @return bool
     */
    protected static function usesSimplePackageStatesFormat() {
        return VersionNumberUtility::convertVersionNumberToInteger(TYPO3_branch)
            >= VersionNumberUtility::convertVersionNumberToInteger('8.0.0');
    }

    /**
     * @return bool
     */
    protected static function usesLegacyExceptionHandlerInterface() {
        return VersionNumberUtility::convertVersionNumberToInteger(TYPO3_branch)
            < VersionNumberUtility::convertVersionNumberToInteger('7.0.0');
    }
}
