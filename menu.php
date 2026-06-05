<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prayer Locations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .content-area {
            padding: 20px;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-dark bg-success shadow-sm">
        <div class="container-fluid">
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <span class="navbar-brand mb-0 h1 mx-auto">PrayerGo</span>
        </div>
    </nav>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMenu">
        <div class="offcanvas-header bg-success text-white">
            <h5 class="offcanvas-title">Menu</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0">
            <div class="list-group list-group-flush">
                <a href="admin_list.php" class="list-group-item list-group-item-action py-3">
                    <i class="bi bi-plus-circle me-2"></i> Admin
                </a>
                <a href="index.php" class="list-group-item list-group-item-action py-3">
                    <i class="bi bi-table me-2"></i> View Database
                </a>
                <a href="create.php" class="list-group-item list-group-item-action py-3">
                    <i class="bi bi-plus-circle me-2"></i> Add New Location
                </a>
                <a href="analysis.php" class="list-group-item list-group-item-action py-3">
                    <i class="bi bi-graph-up me-2"></i> PrayerGo analysis
                </a>
                
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>