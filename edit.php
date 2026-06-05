<?php
require_once 'config.php';
require_once 'menu.php'; 

// 1. Check if ID is provided
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

// 2. Handle Form Submission (UPDATE)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE location_details SET 
        type = ?, 
        location_name = ?, 
        city_name = ?, 
        state_name = ?, 
        latitude = ?, 
        longitude = ?, 
        reporter_name = ? 
        WHERE id = ?");

    $stmt->execute([
        $_POST['type'],
        $_POST['location_name'],
        $_POST['city_name'],
        $_POST['state_name'],
        $_POST['latitude'],
        $_POST['longitude'],
        $_POST['reporter_name'],
        $id
    ]);

    echo "<script>alert('Location Updated Successfully!'); window.location.href='index.php';</script>";
    exit;
}

// 3. Fetch Existing Data to Pre-fill Form
$stmt = $pdo->prepare("SELECT * FROM location_details WHERE id = ?");
$stmt->execute([$id]);
$row = $stmt->fetch();

if (!$row) {
    die("Location not found.");
}
?>

<div class="content-area container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Edit Location</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        
                        <div class="mb-3">
                            <label>Location Name</label>
                            <input type="text" name="location_name" class="form-control" 
                                   value="<?= htmlspecialchars($row['location_name']) ?>" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Type</label>
                                <select name="type" class="form-select">
                                    <option value="mosque" <?= $row['type'] == 'mosque' ? 'selected' : '' ?>>Mosque</option>
                                    <option value="surau" <?= $row['type'] == 'surau' ? 'selected' : '' ?>>Surau</option>
                                    <option value="musollah" <?= $row['type'] == 'musollah' ? 'selected' : '' ?>>Musollah</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Reporter Name</label>
                                <input type="text" name="reporter_name" class="form-control" 
                                       value="<?= htmlspecialchars($row['reporter_name']) ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>City</label>
                                <input type="text" name="city_name" class="form-control" 
                                       value="<?= htmlspecialchars($row['city_name']) ?>" required>
                            </div>
                
                            <div class="col-md-6">
                                <label>State</label>
                                <select name="state_name" class="form-select" required>
                                    <option value="" disabled selected>Select State</option>
                                    <option value="Johor">Johor</option>
                                    <option value="Kedah">Kedah</option>
                                    <option value="Kelantan">Kelantan</option>
                                    <option value="Melaka">Melaka</option>
                                    <option value="Negeri Sembilan">Negeri Sembilan</option>
                                    <option value="Pahang">Pahang</option>
                                    <option value="Perak">Perak</option>
                                    <option value="Perlis">Perlis</option>
                                    <option value="Pulau Pinang">Pulau Pinang</option>
                                    <option value="Sabah">Sabah</option>
                                    <option value="Sarawak">Sarawak</option>
                                    <option value="Selangor">Selangor</option>
                                    <option value="Terengganu">Terengganu</option>
                                    <option value="W.P. Kuala Lumpur">W.P. Kuala Lumpur</option>
                                    <option value="W.P. Labuan">W.P. Labuan</option>
                                    <option value="W.P. Putrajaya">W.P. Putrajaya</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Latitude</label>
                                <input type="number" step="any" name="latitude" class="form-control" 
                                       value="<?= htmlspecialchars($row['latitude']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label>Longitude</label>
                                <input type="number" step="any" name="longitude" class="form-control" 
                                       value="<?= htmlspecialchars($row['longitude']) ?>" required>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Update Location</button>
                            <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>