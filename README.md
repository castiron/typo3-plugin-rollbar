# Rollbar integration for TYPO3

This is more or less the Rollbar library with autoload and config conventions.

### Quick start

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

/**
 * Initialize Error Handling. Do this after the rollbar config. The error handling must get
 * instantiated early on, which is why we don't use helper methods from ExtensionManagementUtility, etc.
 */
require_once(PATH_site . 'typo3conf/ext/rollbar/Classes/Utility/Initializer.php');
\CIC\Rollbar\Utility\Initializer::initErrorHandling();

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

#### TYPO3 Content Object Renderer exceptions

Sometimes the TYPO3 core `ContentObjectRenderer` throws exceptions, which, in production, is amazing because your user,
 on the frontend, will see something like,
 
> Oops, an error occurred! Code: 201705082151141a71f8db

And no other exception will be thrown, nor logged. :sweat:

To remedy this you've got to add a little typoscript to your site. Under the "Includes" tab on your main typoscript
 template, add the `Rollbar Content Exception Handling` typoscript.
 
Best of luck to you in all your endeavors.


