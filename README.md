# Rollbar integration for TYPO3

This is more or less the Rollbar library with autoload and config conventions.

The config can be specified like

```$php
/**
 * Rollbar config
 */
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['rollbar'] = array(
    'rollbar_config' => array(
        'access_token' => '[my-server-side-access-token]',
        'environment' => 'my-environment-name', // This is arbitrary and will scope your reporting in Rollbar
    ),
    /**
     * These are defaults
     */
//    'set_exception_handler' => false,
//    'set_error_handler' => false,
//    'report_fatal_errors' => true,
);

```

Put that in your `typo3conf/AdditionalConfiguration.php`. In general, stuff you put in there is passed directly to 
 `Rollbar::init()`, so you can use 
 [any of the options supported by Rollbar::init()](https://rollbar.com/docs/notifier/rollbar-php/#configuration-reference)
 
NB: The "root" config value (for path mapping with your SCM repo) will be automatically assigned to `PATH_site` for you,
 unless you override it by specifying a value yourself.

If you're using the default `set_exception_handler=false` and `set_error_handler=false`:
 * You'll still get any fatal errors automatically
 * You can manually catch/report report exceptions like so:
```$php
try {
    // ... do a lil dance
} catch (\Exception $e) {
    \Rollbar::report_exception($e);
}
```
