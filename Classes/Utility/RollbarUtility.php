<?php namespace CIC\Rollbar\Utility;
use CIC\Rollbar\Rollbar\Payload\Level;
use Rollbar\Rollbar;

/**
 * Class RollbarUtility
 * @package CIC\Rollbar\Utility
 */
class RollbarUtility {
    /**
     * @param \Error|string $error
     * @param array $extra
     */
    public static function reportError($error, $extra = []) {
        Rollbar::log(Level::error(), $error, $extra);
    }

    /**
     * @param \Error|string $error
     * @param array $extra
     */
    public static function reportWarning($error, $extra = []) {
        Rollbar::log(Level::warning(), $error, $extra);
    }

    /**
     * @param \Error|string $error
     * @param array $extra
     */
    public static function reportNotice($error, $extra = []) {
        Rollbar::log(Level::notice(), $error, $extra);
    }
}
