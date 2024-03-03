<?php

// Load env variables from config.php file
require_once 'config.php';

// Create a new connection to the database
$db = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check the connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}