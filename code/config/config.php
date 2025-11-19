<?php

if (getenv('JAWSDB_URL') !== false) {
    $dbparts = parse_url(getenv('JAWSDB_URL'));

    define('DB_HOST', $dbparts['host']);
    define('DB_NAME', ltrim($dbparts['path'], '/'));
    define('DB_USER', $dbparts['user']);
    define('DB_PASS', $dbparts['pass']);
} else {
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'ecoride');
    define('DB_USER', 'root');
    define('DB_PASS', '');
}
