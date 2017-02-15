<?php namespace CIC\Rollbar\Error;
use TYPO3\CMS\Core\Error\ProductionExceptionHandler as Typo3ProductionExceptionHandler;
use TYPO3\CMS\Core\Error\ExceptionHandlerInterface;

/**
 * Class ExceptionHandler
 * @package CIC\Rollbar\Error
 */
class ProductionExceptionHandler extends Typo3ProductionExceptionHandler implements ExceptionHandlerInterface {
    /**
     * @param \Exception|\Throwable $exception
     */
    public function handleException($exception) {
        if ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['rollbar']['set_exception_handler']) {
            \Rollbar::report_exception($exception);
        }
        parent::handleException($exception);
    }
}