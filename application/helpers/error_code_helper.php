<?php
/**
 * Values for various error codes. The codes are mostly matched to HTTP status
 * codes, but some of them might not be perfect matches.
 */
define("PROHIBITED", 405);
define("BAD_DATA", 400);
define("BAD_CREDENTIALS", 403);
define("UNAUTHORIZED", 403);
define("NO_COOKIE", 409);
define("SUCCESS", 200);

function exception_error_handler($errno, $errstr, $errfile, $errline ) {
    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
}
set_error_handler("exception_error_handler");