<?php
// login.php
require_once 'config.php';

$debug_message = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM admin WHERE email = ?");
    $stmt->execute([$email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin) {
        $error = "❌ Account not found. Check the email spelling.";
    } else {
        // We found the user, now let's check the password
        $db_password = $admin['password'];

        // CHECK 1: Is it a Hash? (Secure)
        $is_hash_match = password_verify($password, $db_password);

        // CHECK 2: Is it Plain Text? (Insecure, but good to check)
        $is_plain_match = ($password == $db_password);

        if ($is_hash_match) {
            // SUCCESS!
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['full_name'] = $admin['full_name'];
            header("Location: index.php");
            exit;
        } elseif ($is_plain_match) {
            // It matches, but your database is storing plain text!
            // We allow login, but warn you.
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['full_name'] = $admin['full_name'];
            header("Location: index.php");
            exit;
        } else {
            // FAILED BOTH
            $error = "Password Salah.";
            
            // Generate Debug Info
            $debug_message .= "<strong>Debug Info:</strong><br>";
            $debug_message .= "Input Password: " . htmlspecialchars($password) . "<br>";
            $debug_message .= "Stored DB Password: " . htmlspecialchars(substr($db_password, 0, 15)) . "...<br>";
            $debug_message .= "DB Password Length: " . strlen($db_password) . " (Should be 60 for hash)<br>";
            $debug_message .= "Hash Check: " . ($is_hash_match ? "YES" : "NO") . "<br>";
            $debug_message .= "Plain Check: " . ($is_plain_match ? "YES" : "NO") . "<br>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Debug</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width:400px">
    <div class="card shadow">
        <div class="card-body">
            <h4 class="text-center mb-4">Admin Login</h4>

            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <?= $error ?>
                    <?php if ($debug_message): ?>
                        <hr>
                        <div style="font-size:0.8rem; overflow-wrap: break-word;">
                            <?= $debug_message ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input class="form-control" type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input class="form-control" type="password" name="password" required>
                </div>
                <button class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>