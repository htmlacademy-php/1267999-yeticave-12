<?php
session_start();
require_once ('helpers.php');
require_once('db.php');
$categories = get_categories($con);
$authorization_error = http_response_code(403);
const MIN_VALUE = 5;
const MAX_VALUE = 256;
