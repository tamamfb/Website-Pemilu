<?php
session_start();
include '../database/connect.php'; 
if (!isset($_SESSION['email']) || $_SESSION['role'] != 0) {
    header("Location: ../home/login.php");
    exit();
}

$query_bem = "SELECT U_BEM, COUNT(*) AS total_votes FROM User WHERE U_BEM IS NOT NULL GROUP BY U_BEM";
$result_bem = mysqli_query($conn, $query_bem);


$votes_bem = [0, 0, 0]; 
while ($row = mysqli_fetch_assoc($result_bem)) {
    $votes_bem[$row['U_BEM'] - 1] = $row['total_votes'];
}

$query_blm = "SELECT U_BLM, COUNT(*) AS total_votes FROM User WHERE U_BLM IS NOT NULL GROUP BY U_BLM";
$result_blm = mysqli_query($conn, $query_blm);


$votes_blm = [0, 0]; 
while ($row = mysqli_fetch_assoc($result_blm)) {
    $votes_blm[$row['U_BLM'] - 1] = $row['total_votes'];
}


echo json_encode([
    'votes_bem' => $votes_bem,
    'votes_blm' => $votes_blm,
]);
?>
