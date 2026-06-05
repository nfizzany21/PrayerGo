<?php
// FILE: insert_location.php
header('Content-Type: text/plain');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // 1. Database Connection
    $con = new mysqli("localhost", "root", "", "prayer_go_db");
    $con->set_charset("utf8mb4");

    // 2. Get Variables from POST (including City & State)
    $name     = $_POST['location_name'] ?? '';
    $type     = $_POST['type'] ?? '';
    $city     = $_POST['city'] ?? '';        // NEW
    $state    = $_POST['state'] ?? '';       // NEW
    $lat      = $_POST['latitude'] ?? '';
    $long     = $_POST['longitude'] ?? '';
    $reporter = $_POST['reporter_name'] ?? '';
    $date     = $_POST['report_date'] ?? '';

    // 3. Auto-detect User Agent
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown Android Device';

    // 4. Validate Required Fields
    if (empty($name) || empty($lat) || empty($long)) {
        throw new Exception("Missing required fields");
    }

    // 5. Prepare SQL (NOW WITH city & state)
    $stmt = $con->prepare("
        INSERT INTO location_details 
        (location_name, type, city_name, state_name, latitude, longitude, reporter_name, report_date, user_agent) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    // 6. Bind Parameters
    $stmt->bind_param(
        "sssssssss",
        $name,
        $type,
        $city,
        $state,
        $lat,
        $long,
        $reporter,
        $date,
        $userAgent
    );

    // 7. Execute
    $stmt->execute();

    echo "success";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
