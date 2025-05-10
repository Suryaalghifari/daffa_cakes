<?php
session_start();
require_once '../../config/base_url.php';

$_SESSION = [];
session_unset();
session_destroy();

if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");

header("Location: " . BASE_URL . "views/halamanweb/index.php?logout=1");
exit;
