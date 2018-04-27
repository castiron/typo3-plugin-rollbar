<?php namespace CIC\Rollbar\Error;

use Rollbar\Payload\Level;
use Rollbar\Rollbar;
use TYPO3\CMS\Frontend\ContentObject\AbstractContentObject;
use TYPO3\CMS\Core\Error\ProductionExceptionHandler;

/**
 * Class ContentObjectExceptionHandler
 * @package CIC\Rollbar\Error
 */
class ContentObjectExceptionHandler extends ProductionExceptionHandler {
    /**
     * @param \Exception $exception
     * @param AbstractContentObject|null $contentObject
     * @param array $_contentObjectConfiguration
     * @throws \Exception
     */
    public function handle(\Exception $exception, AbstractContentObject $contentObject = null, $_contentObjectConfiguration = []) {
        Rollbar::log(Level::ERROR, $exception, ['cObject' => $contentObject]);
        if (static::backendUserIsPresent()) {
            throw $exception;
        }
        parent::handleException($exception);
    }

    /**
     *
     */
    protected static function backendUserIsPresent() {
        return $GLOBALS['BE_USER']->user['uid'] ? true : false;
    }
}
