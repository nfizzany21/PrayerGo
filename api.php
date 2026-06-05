<?php
// api.php
require_once 'config.php';

// Set headers so the browser/client knows this is JSON data
header('Content-Type: application/json');
// Optional: Allow access from other domains (useful if your mobile app is hosted elsewhere)
header("Access-Control-Allow-Origin: *");

try {
    // Select ALL records from location_details
    // We removed "WHERE report_date >= NOW() - INTERVAL 36 HOUR" 
    // because locations are permanent data, not temporary events.
    $sql = "SELECT * FROM location_details ORDER BY report_date DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Fetch data as an associative array
    $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output the array as JSON
    echo json_encode($locations, JSON_PRETTY_PRINT);

} catch (PDOException $e) {
    // If there is an error, output a JSON error message
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>