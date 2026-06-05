<?php
require_once 'config.php';
require_once 'menu.php'; // Loads the menu burger

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic INSERT Logic
    $stmt = $pdo->prepare("INSERT INTO location_details 
        (id, type, location_name, city_name, state_name, latitude, longitude, reporter_name, report_date) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $new_id = 'LOC' . substr(uniqid(), -5); 
    
    $stmt->execute([
        $new_id,
        $_POST['type'],
        $_POST['location_name'],
        $_POST['city_name'],
        $_POST['state_name'],
        $_POST['latitude'],
        $_POST['longitude'],
        $_POST['reporter_name'],
        date('Y-m-d H:i:s')
    ]);

    echo "<script>alert('Saved!'); window.location.href='index.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Location</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="content-area container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Add New Entry</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label>Location Name</label>
                            <input type="text" name="location_name" class="form-control" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Type</label>
                                <select name="type" class="form-select">
                                    <option value="mosque">Mosque</option>
                                    <option value="surau">Surau</option>
                                    <option value="musollah">Musollah</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Reporter Name</label>
                                <input type="text" name="reporter_name" class="form-control" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>City</label>
                                <input type="text" name="city_name" class="form-control" required>
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
                                <input type="number" step="any" name="latitude" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label>Longitude</label>
                                <input type="number" step="any" name="longitude" class="form-control" required>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">Save Location</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>