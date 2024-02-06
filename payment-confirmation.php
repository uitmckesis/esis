<?php
session_start();
include('includes/config.php');

// Check if the session is valid
if(strlen($_SESSION['login']) == 0) {
   header('location:index.php');
   exit();
}

// Function to update booking status
function updatePaymentStatus($dbh, $bookingId, $paymentStatus, $transactionId, $paymentDate, $paymentTime) {
    try {
        $sql = "INSERT INTO tblpayment (BookingId, PaymentStatus, TransactionId, PaymentDate, PaymentTime) VALUES (:bookingId, :paymentStatus, :transactionId, :paymentDate, :paymentTime)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':bookingId', $bookingId, PDO::PARAM_INT);
        $query->bindParam(':paymentStatus', $paymentStatus, PDO::PARAM_STR);
        $query->bindParam(':transactionId', $transactionId, PDO::PARAM_STR);
        $query->bindParam(':paymentDate', $paymentDate, PDO::PARAM_STR);
        $query->bindParam(':paymentTime', $paymentTime, PDO::PARAM_STR);
        $query->execute();
        return true;
    } catch (PDOException $e) {
        // Handle exception
        error_log("Database error: " . $e->getMessage());
        return false;
    }
}

// Assuming you receive PayPal transaction data via POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $paymentID = $_POST['paymentID']; // PayPal payment ID
    $payerID = $_POST['payerID']; // PayPal payer ID
    $bookingId = $_POST['bookingId']; // Booking ID from your session or database

    // TODO: Call PayPal API to capture payment for the given paymentID and payerID

    // On successful payment capture
    if ($paymentSuccess) {
        // Update booking status in your database
        $paymentStatus = 'Completed';
        $transactionId = 'PAYPAL_TRANSACTION_ID'; // Replace with actual PayPal transaction ID
        $updateSuccess = updateBookingStatus($dbh, $bookingId, $paymentStatus, $transactionId);

        if ($updateSuccess) {
            echo json_encode(['success' => true, 'message' => 'Payment successful and booking updated.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Payment successful but booking update failed.']);
        }
    } else {
        // Handle payment failure
        echo json_encode(['success' => false, 'message' => 'Payment failed.']);
    }
} else {
    // Handle invalid request method
    header("HTTP/1.1 405 Method Not Allowed");
    exit();
}
?>