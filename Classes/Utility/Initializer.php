<?php namespace CIC\Rollbar\Utility;

/**
 * Class Initializer
 * @package CIC\Rollbar\Utility
 */
class Initializer {
    public static function initErrorHandling() {
        static::registerAutoloader();
        static::setErrorHandlingConfig();
    }

    /**
     * We need to register a custom autoloader because the TYPO3 core doesn't initialize autoloaders until after
     * error handling is set up.
     */
    protected static function registerAutoloader() {
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
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['errorHandler'] = \CIC\Rollbar\Error\ErrorHandler::class;
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['debugExceptionHandler'] = \CIC\Rollbar\Error\ExceptionHandler::class;
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['productionExceptionHandler'] = \CIC\Rollbar\Error\ProductionExceptionHandler::class;
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['errors']['exceptionHandler'] = $GLOBALS['TYPO3_CONF_VARS']['SYS']['productionExceptionHandler'];
    }
}
