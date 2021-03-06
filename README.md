# Rollbar integration for TYPO3

This is more or less the Rollbar library with autoload and config conventions.

### A note on composer mode

To make this plugin compatible with TYPO3 installations in both
"[Composer mode](https://wiki.typo3.org/Composer#TYPO3_7LTS_and_later)" and legacy non-Composer mode, we cannot
"require" Rollbar automatically in `composer.json` (sorry!). That's because, even in non-composer mode, TYPO3 (v6, for
example) will throw an error that `rollbar/rollbar` is not present in the system, because (apparently) TYPO3 reads the
`composer.json` file and tries to understand dependencies based on it, regardless of which mode you're using.

So: If you include this plugin via Composer (using Composer mode), you will need to manually include the dependency
`rollbar/rollbar` along with it -- it will be suggested but not auto-installed as a dependency.

### A note on autoloading (non-Composer mode)

We don't include the Rollbar library with this extension. If you're including this plugin the old-school way (through
the extension manager, or via a Git submodule), you won't have the Rollbar library or its internal dependencies on hand.
You must autoload them with Composer. One way to do this is like so:

1. Add composer.json to your project root, adding `rollbar/rollbar` as a dependency
1. `composer install`
1. Include the composer `vendor/autoload.php` in the project by `require_once`ing it from the top of the file
 `typo3conf/AdditionalConfiguration.php`.
1. If you're using a deployment system (like Capistrano) or other automated deployment approach, you'll need to be sure
 and add a `composer install` step to your deployments :wink:

### Quick start

The config can be specified like

```$php
/**
 * Rollbar config
 */
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['rollbar'] = array(
    'rollbar_enabled' => true, // You can use this to turn it off/on
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
 * You can manually catch/report exceptions like so:
```$php
try {
    // ... do a lil dance
} catch (\Exception $e) {
    /**
    * Send the exception to Rollbar
    */
    \CIC\Rollbar\Utility\RollbarUtility::reportError($e);

    /**
    * Or you could do one of these:
    *
    * \CIC\Rollbar\Utility\RollbarUtility::reportWarning($e);
    * \CIC\Rollbar\Utility\RollbarUtility::reportNotice($e);
    */
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


