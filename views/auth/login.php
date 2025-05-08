<?php
session_start();
require_once __DIR__ . '/../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $query = mysqli_prepare($conn, "SELECT * FROM user WHERE username = ?");
    mysqli_stmt_bind_param($query, "s", $username);
    mysqli_stmt_execute($query);
    $result = mysqli_stmt_get_result($query);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
       $_SESSION['user'] = [
            'id' => $user['user_id'],
            'username' => $user['username'],
            'role' => $user['role'],
            'foto' => $user['foto']
        ];

        
        switch ($user['role']) {
            case 'owner':
                header("Location: /daffa_cakes/views/dashboard/owner/index.php");
                break;
            case 'admin':
                header("Location: /daffa_cakes/views/dashboard/admin/index.php");
                break;
            case 'kasir':
                header("Location: /daffa_cakes/views/dashboard/kasir/index.php");
                break;
            default:
                $_SESSION['error'] = "Role tidak dikenali.";
                header("Location: login.php");
                break;
        }
        exit;
    } else {
        $_SESSION['error'] = "Username atau password salah.";
        header("Location: login.php");
        exit;
    }
}
?>

<!-- FORM LOGIN -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Daffa Cakes</title>
    <link href="/daffa_cakes/sb-admin/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="/daffa_cakes/sb-admin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-primary">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 mt-5">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-5">
                    <div class="text-center">
                        <h4 class="h4 text-gray-900 mb-4"><b>Login Daffa Cakes</b></h4>
                    </div>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger text-center">
                            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                   <form method="POST" class="user" autocomplete="off">
                        <div class="form-group">
                            <input type="text" name="username" class="form-control form-control-user" placeholder="Username" required autofocus>
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" class="form-control form-control-user" placeholder="Password" required>
                        </div>
                        <button class="btn btn-primary btn-user btn-block" type="submit">
                            <i class="fa fa-lock"></i> Login
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="/daffa_cakes/sb-admin/vendor/jquery/jquery.min.js"></script>
<script src="/daffa_cakes/sb-admin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/daffa_cakes/sb-admin/js/sb-admin-2.min.js"></script>
</body>
</html>
