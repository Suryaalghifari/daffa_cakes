<?php
session_start();

// Hapus semua data session
session_unset();
session_destroy();

// Redirect ke halaman login
header("Location: /daffa_cakes/views/auth/login.php");
exit;
