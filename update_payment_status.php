<?php
include('includes/config.php');

// Check if the request is from a valid source and all required data is present
if (isset($_POST['bookingid']) && isset($_POST['paymentstatus']) && isset($_POST['paymentdate']) && isset($_POST['paymenttime'])) {
    $bookingid = intval($_POST['bookingid']); // Ensure integer type
    $paymentstatus = $_POST['paymentstatus'];
    $paymentdate = $_POST['paymentdate'];
    $paymenttime = $_POST['paymenttime'];

    // Prepare SQL query to update payment status
    $sql = "UPDATE tblpayment SET paymentstatus=:paymentstatus, paymentdate=:paymentdate, paymenttime=:paymenttime WHERE bookingid=:bookingid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':bookingid', $bookingid, PDO::PARAM_INT); // Bind as integer
    $query->bindParam(':paymentstatus', $paymentstatus);
    $query->bindParam(':paymentdate', $paymentdate);
    $query->bindParam(':paymenttime', $paymenttime);

    // Execute the query
    if ($query->execute()) {
        echo "Payment status updated successfully.";
    } else {
        echo "Error updating payment status.";
    }
}
?>
