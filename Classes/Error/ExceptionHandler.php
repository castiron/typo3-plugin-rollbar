<?php namespace CIC\Rollbar\Error;
use TYPO3\CMS\Core\Error\DebugExceptionHandler;
use TYPO3\CMS\Core\Error\ExceptionHandlerInterface;

/**
 * Class ExceptionHandler
 * @package CIC\Rollbar\Error
 */
class ExceptionHandler extends \t3lib_error_DebugExceptionHandler {
    /**
     * @param \Exception $exception
     */
    public function handleException(\Exception $exception) {
        parent::handleException($exception);
        if ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['rollbar']['set_exception_handler']) {
            \Rollbar::report_exception($exception);
        }
    }
}
