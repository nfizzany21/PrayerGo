<?php
require_once 'config.php';
require_once 'menu.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) die("Invalid ID");

$stmt = $pdo->prepare("SELECT * FROM admin WHERE id=?");
$stmt->execute([$id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$admin) die("Admin not found");

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare(
            "UPDATE admin SET full_name=?, email=?, gender=?, password=? WHERE id=?"
        );
        $stmt->execute([$full_name, $email, $gender, $password, $id]);
    } else {
        $stmt = $pdo->prepare(
            "UPDATE admin SET full_name=?, email=?, gender=? WHERE id=?"
        );
        $stmt->execute([$full_name, $email, $gender, $id]);
    }

    $msg = "Admin updated successfully";
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Edit Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

<h4>Edit Admin</h4>

<?php if ($msg): ?><div class="alert alert-success"><?= $msg ?></div><?php endif; ?>

<form method="POST">
<input class="form-control mb-2" name="full_name" value="<?= htmlspecialchars($admin['full_name']) ?>" required>
<input class="form-control mb-2" name="email" type="email" value="<?= htmlspecialchars($admin['email']) ?>" required>

<select class="form-select mb-2" name="gender">
    <option <?= $admin['gender']=='Male'?'selected':'' ?>>Male</option>
    <option <?= $admin['gender']=='Female'?'selected':'' ?>>Female</option>
</select>

<input class="form-control mb-3" name="password" type="password" placeholder="New Password (optional)">

<button class="btn btn-primary">Update</button>
<a href="admin_list.php" class="btn btn-secondary">Back</a>
</form>

</body>
</html>
