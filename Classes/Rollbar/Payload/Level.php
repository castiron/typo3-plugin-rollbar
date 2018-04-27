<?php namespace CIC\Rollbar\Rollbar\Payload;

/**
 * Class Level
 * @package CIC\Rollbar\Rollbar\Payload
 */
class Level extends \Rollbar\Payload\Level {

    /**
     * @param $phpErrorLevelNumber
     * @return null
     */
    public static function fromPhpErrorLevel($phpErrorLevelNumber) {
        $rollbarLevelName = static::phpLevelToRollbarLevelName($phpErrorLevelNumber);
        return $rollbarLevelName ?: null;
    }

    /**
     * @param $level
     * @return string|null
     */
    protected static function phpLevelToRollbarLevelName($level) {
        $mappings = static::levelMappings();
        return $mappings[$level] ?: null;
    }

    /**
     * @return array
     */
    protected static function levelMappings() {
        return [
            E_WARNING => 'warning',
            E_NOTICE => 'info',
            E_USER_ERROR => 'error',
            E_USER_WARNING => 'warning',
            E_USER_NOTICE => 'info',
            E_STRICT => 'info',
            E_RECOVERABLE_ERROR => 'error',
            E_DEPRECATED => 'notice',
        ];
    }
}
