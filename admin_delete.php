<?php
require_once 'config.php';
require_once 'menu.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) die("Invalid ID");

/* elak admin delete diri sendiri */
if ($id == $_SESSION['admin_id']) {
    die("You cannot delete your own account.");
}

$stmt = $pdo->prepare("DELETE FROM admin WHERE id=?");
$stmt->execute([$id]);

header("Location: admin_list.php");
exit;
