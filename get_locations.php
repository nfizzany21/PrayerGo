<?php
header('Content-Type: application/json');
$con = mysqli_connect("localhost", "root", "", "prayer_go_db");

$sql = "SELECT location_name, type, latitude, longitude, reporter_name, report_date FROM location_details";
$result = mysqli_query($con, $sql);

$response = array();

while($row = mysqli_fetch_assoc($result)){
    $response[] = $row;
}

echo json_encode($response);
?>