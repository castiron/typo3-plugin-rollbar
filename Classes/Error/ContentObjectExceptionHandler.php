<?php namespace CIC\Rollbar\Error;

use TYPO3\CMS\Frontend\ContentObject\AbstractContentObject;
use TYPO3\CMS\Frontend\ContentObject\Exception\ProductionExceptionHandler;

/**
 * Class ContentObjectExceptionHandler
 * @package CIC\Rollbar\Error
 */
class ContentObjectExceptionHandler extends ProductionExceptionHandler {
    /**
     * @param \Exception $exception
     * @param AbstractContentObject|null $contentObject
     * @param array $contentObjectConfiguration
     * @return string
     * @throws \Exception
     */
    public function handle(\Exception $exception, AbstractContentObject $contentObject = null, $contentObjectConfiguration = []) {
        \Rollbar::report_exception($exception, $contentObject);
        if (static::backendUserIsPresent()) {
            throw $exception;
        }
        return parent::handle($exception, $contentObject, $contentObjectConfiguration);
    }

    /**
     *
     */
    protected static function backendUserIsPresent() {
        return $GLOBALS['BE_USER']->user['uid'] ? true : false;
    }
}
