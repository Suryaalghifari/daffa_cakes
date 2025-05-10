<?php
if (!isset($_SESSION['pelanggan'])) {
    header("Location: login.php");
    exit;
}
