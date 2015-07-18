<?php
    define('DEBUG_MODE', true);
    
    // Show errors in debug mode
    if ($debug_mode) {
        ini_set('display_errors',1);
        error_reporting(E_ALL);
    }

    //
    // COMMON FUNCTIONS
    //

    // FatalError function
    // Only show error messages in debug mode, otherwise die silently
    // TODO: Log errors to file
    function fatalError($errorMsg) {
        if (DEBUG_MODE) {
            echo '<h3>Error</h3>';
            echo '<p>'.$errorMsg.'</p>';
        }
        die();
    }
    
    // Sanitise function
    // Based on code from http://www.w3schools.com/php/php_form_validation.asp
    // TODO: replace with preg checks
    function sanitise($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    
    //
    // END COMMON FUNCTIONS
    //
    
?>