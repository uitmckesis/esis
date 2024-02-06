<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
    exit();
}

if (isset($_GET['bookingid'])) {
    $bookingId = $_GET['bookingid'];
    cancelBooking($bookingId);
    // Redirect the user to the My Booking page or any other page after cancellation
    header('location:my-booking.php');
    exit();
} else {
    // Invalid booking ID
    echo "Invalid booking ID.";
    exit();
}

function cancelBooking($bookingId) {
    global $dbh;

    try {
        // Update the booking status to cancelled
        $sql = "UPDATE tblbooking SET Status = 'Cancelled' WHERE id = :bookingId";
        $query = $dbh->prepare($sql);
        $query->bindParam(':bookingId', $bookingId, PDO::PARAM_INT);
        $query->execute();
    } catch (PDOException $e) {
        // Handle errors (you can log or display an error message)
        echo "Unable to cancel booking. Please try again.";
    }
}
?>
