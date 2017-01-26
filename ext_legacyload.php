<?php
$base = t3lib_extMgm::extPath('rollbar');
foreach (array(
             'Classes/Vendor/rollbar.php',
             'Classes/Error/ErrorHandler.php',
             'Classes/Error/ExceptionHandler.php',
             'Classes/Error/ProductionExceptionHandler.php',
         ) as $file) {
    require_once($base . $file);
}

