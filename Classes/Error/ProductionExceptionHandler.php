<?php namespace CIC\Rollbar\Error;
use Rollbar\Payload\Level;
use Rollbar\Rollbar;
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
    public function handleException(\Throwable $exception) {
        if ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['rollbar']['set_exception_handler']) {
            Rollbar::log(Level::ERROR, $exception);
        }
        parent::handleException($exception);
    }
}
