<?php namespace CIC\Rollbar\Error;
use Rollbar\Payload\Level;
use Rollbar\Rollbar;
use TYPO3\CMS\Core\Error\DebugExceptionHandler;
use TYPO3\CMS\Core\Error\ExceptionHandlerInterface;

/**
 * Class ExceptionHandler
 * @package CIC\Rollbar\Error
 */
class ExceptionHandler extends DebugExceptionHandler implements ExceptionHandlerInterface {
    /**
     * @param \Exception|\Throwable $exception
     */
    public function handleException(\Exception $exception) {
        if ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['rollbar']['set_exception_handler']) {
            Rollbar::log(Level::error(), $exception);
        }
        parent::handleException($exception);
    }
}
