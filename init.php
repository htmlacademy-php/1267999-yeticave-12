<?php
session_start();
require_once('vendor/autoload.php');
require_once('helpers.php');
require_once('db.php');
$categories = get_categories($con);
const MIN_VALUE = 5;
const MAX_VALUE = 256;
const LIMIT_SAMPLE_LOT = 6;
const MIN_SIZE_FILE = 2000000;
$user = [
    'name' => $_SESSION['name'],
    'id' => $_SESSION['id']
];
