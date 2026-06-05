<?php
require_once 'config.php';
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM location_details WHERE id = ?");
    $stmt->execute([$_GET['id']]);
}
header("Location: index.php");
exit;
?>