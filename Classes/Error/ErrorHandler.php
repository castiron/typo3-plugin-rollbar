<?php namespace CIC\Rollbar\Error;

use TYPO3\CMS\Core\Error\ErrorHandler as Typo3ErrorHandler;
use TYPO3\CMS\Core\Error\ErrorHandlerInterface;

/**
 * Class ExceptionHandler
 * @package CIC\Rollbar\Error
 */
class ErrorHandler extends Typo3ErrorHandler implements ErrorHandlerInterface {
    /**
     * @param int $errorLevel
     * @param string $errorMessage
     * @param string $errorFile
     * @param int $errorLine
     * @return bool
     */
    public function handleError($errorLevel, $errorMessage, $errorFile, $errorLine) {
        $out = parent::handleError($errorLevel, $errorMessage, $errorFile, $errorLine); // TODO: Change the autogenerated stub
        if ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['rollbar']['set_error_handler']) {
            \Rollbar::report_php_error($errorLevel, $errorMessage, $errorFile, $errorLine);
        }
        return $out;
    }
}