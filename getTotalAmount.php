<?php
include('includes/config.php');

$useremail = $_SESSION['login'];
$sql = "SELECT TotalPrice FROM tblbooking WHERE userEmail = :useremail";
$query = $dbh->prepare($sql);
$query->bindParam(':useremail', $useremail, PDO::PARAM_STR);
$query->execute();
$result = $query->fetch(PDO::FETCH_ASSOC);

echo json_encode($result);
?>