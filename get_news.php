<?php
// FILE: get_news.php
header('Content-Type: text/plain');
$con = mysqli_connect("localhost", "root", "", "prayer_go_db");

// Get the very latest news item
$sql = "SELECT message FROM news_updates ORDER BY id DESC LIMIT 1";
$result = mysqli_query($con, $sql);

if ($row = mysqli_fetch_assoc($result)) {
    echo $row['message'];
} else {
    echo "No news available.";
}
?>