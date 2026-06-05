<?php
require_once 'config.php';
require_once 'menu.php';

// --- DATA FETCHING FOR ANALYSIS ---

// 1. Total Reports
$total_stmt = $pdo->query("SELECT COUNT(*) FROM location_details");
$total_reports = $total_stmt->fetchColumn();

// 2. Counts by Type (for Pie Chart)
$type_stmt = $pdo->query("SELECT type, COUNT(*) as count FROM location_details GROUP BY type");
$type_data = $type_stmt->fetchAll(PDO::FETCH_ASSOC);

// 3. Counts by City (Top 5 for Bar Chart)
$city_stmt = $pdo->query("SELECT city_name, COUNT(*) as count FROM location_details GROUP BY city_name ORDER BY count DESC LIMIT 5");
$city_data = $city_stmt->fetchAll(PDO::FETCH_ASSOC);

// 4. Recent Trend (Last 7 Days)
$trend_stmt = $pdo->query("SELECT DATE(report_date) as r_date, COUNT(*) as count FROM location_details GROUP BY DATE(report_date) ORDER BY r_date DESC LIMIT 7");
$trend_data = array_reverse($trend_stmt->fetchAll(PDO::FETCH_ASSOC)); // Reverse to show oldest to newest

// 5. Fetch All Coordinates for the Map
$map_stmt = $pdo->query("SELECT location_name, latitude, longitude, type FROM location_details");
$map_data = $map_stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare JSON for JavaScript
$json_types = json_encode(array_column($type_data, 'type'));
$json_type_counts = json_encode(array_column($type_data, 'count'));

$json_cities = json_encode(array_column($city_data, 'city_name'));
$json_city_counts = json_encode(array_column($city_data, 'count'));

$json_trend_dates = json_encode(array_column($trend_data, 'r_date'));
$json_trend_counts = json_encode(array_column($trend_data, 'count'));

$json_map_data = json_encode($map_data);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Analysis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map { height: 400px; width: 100%; border-radius: 8px; }
        .card-icon { font-size: 2rem; opacity: 0.2; position: absolute; right: 15px; top: 15px; }
    </style>
</head>
<body class="bg-light">

<div class="container mt-4 mb-5">
    <h2 class="mb-4">System Analysis</h2>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-start border-primary border-4">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase">Total Reports</h6>
                    <h2 class="mb-0"><?= $total_reports ?></h2>
                    <i class="bi bi-file-earmark-text card-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-start border-success border-4">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase">Active Cities</h6>
                    <h2 class="mb-0"><?= count($city_data) ?></h2>
                    <i class="bi bi-geo-alt card-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-start border-warning border-4">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase">Most Frequent Type</h6>
                    <h2 class="mb-0"><?= $type_data[0]['type'] ?? 'N/A' ?></h2>
                    <i class="bi bi-exclamation-triangle card-icon"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Geographic Distribution</h5>
        </div>
        <div class="card-body p-0">
            <div id="map"></div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">Reports by Type</div>
                <div class="card-body">
                    <canvas id="typeChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">Top 5 Cities</div>
                <div class="card-body">
                    <canvas id="cityChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // --- 1. CHART.JS CONFIGURATION ---
    
    // Type Chart (Doughnut)
    const ctxType = document.getElementById('typeChart').getContext('2d');
    new Chart(ctxType, {
        type: 'doughnut',
        data: {
            labels: <?= $json_types ?>,
            datasets: [{
                data: <?= $json_type_counts ?>,
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
                hoverOffset: 4
            }]
        }
    });

    // City Chart (Bar)
    const ctxCity = document.getElementById('cityChart').getContext('2d');
    new Chart(ctxCity, {
        type: 'bar',
        data: {
            labels: <?= $json_cities ?>,
            datasets: [{
                label: 'Number of Reports',
                data: <?= $json_city_counts ?>,
                backgroundColor: '#36b9cc',
                borderRadius: 5
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // --- 2. LEAFLET MAP CONFIGURATION ---
    
    // Initialize map centered on a default location (e.g., Kuala Lumpur) or the first data point
    var map = L.map('map').setView([3.140853, 101.693207], 6); 

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Load markers from PHP data
    var locations = <?= $json_map_data ?>;
    
    if (locations.length > 0) {
        // Create a bounds object to fit map to markers
        var bounds = L.latLngBounds();

        locations.forEach(function(loc) {
            if(loc.latitude && loc.longitude) {
                var marker = L.marker([loc.latitude, loc.longitude]).addTo(map);
                marker.bindPopup("<b>" + loc.location_name + "</b><br>" + loc.type);
                bounds.extend([loc.latitude, loc.longitude]);
            }
        });

        // Auto-zoom map to fit all markers
        map.fitBounds(bounds);
    }
</script>

</body>
</html>