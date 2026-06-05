<?php
require_once 'config.php';

// The email you want to fix
$email = 'admin@prayergo.com';

// The new password you want to use
$new_pass = '123456'; 

// Generate the secure hash
$new_hash = password_hash($new_pass, PASSWORD_DEFAULT);

// Update the database
$stmt = $pdo->prepare("UPDATE admin SET password = ? WHERE email = ?");
$stmt->execute([$new_hash, $email]);

echo "<h1>Password Reset Complete</h1>";
echo "User: $email<br>";
echo "New Password: <b>$new_pass</b><br>";
echo "New Hash: $new_hash<br><br>";
echo "<a href='login.php'>Click here to Login</a>";
?>