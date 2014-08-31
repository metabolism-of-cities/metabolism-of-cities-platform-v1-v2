<?php
// This file defines certain constants that are unique for your server. 
// Review and adjust these settings to get started.

define("URL", "http://localhost/mfa/"); // Full URL
define("PRODUCTION", false); // Set to false when working locally, true when in production
define("LOCAL",true); // This one is the other way around
define("ADMIN", true); // Set this to true to be logged in as an Admin locally without having to actually log in
define("UPLOAD_PATH", "/var/www/mfa/files/"); // Documents will be uploaded here. Make it non-publicly accessible in production.
define("SALT", ""); // You MUST set this salt in order for the encrypt() function to work... any random string will do
define("WEBMASTER_MAIL", "email@example.com"); // Will be notified of errors, new publications, etc.


// MySQL connection settings

define("SERVER","localhost");
define("USER","mfa_user");
define("PASSWORD","metabolism");
define("DATABASE","mfa");
?>
