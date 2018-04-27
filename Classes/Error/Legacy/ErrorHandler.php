<?php namespace CIC\Rollbar\Error\Legacy;

use CIC\Rollbar\Rollbar\Payload\Level;
use Rollbar\Rollbar;
use TYPO3\CMS\Core\Error\ErrorHandler as Typo3ErrorHandler;
use TYPO3\CMS\Core\Error\ErrorHandlerInterface;

/**
 * Class ErrorHandler
 * @package CIC\Rollbar\Error\Legacy
 */
class ErrorHandler extends Typo3ErrorHandler implements ErrorHandlerInterface {
    /**
     * @param int $errorLevel
     * @param string $errorMessage
     * @param string $errorFile
     * @param int $errorLine
     * @return bool
     * @throws \TYPO3\CMS\Core\Error\Exception
     */
    public function handleError($errorLevel, $errorMessage, $errorFile, $errorLine) {
        if ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['rollbar']['set_error_handler']) {
            Rollbar::log(Level::fromPhpErrorLevel($errorLevel), $errorMessage);
        }

        return parent::handleError($errorLevel, $errorMessage, $errorFile, $errorLine);
    }
}
