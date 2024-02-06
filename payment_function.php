<?php

function initiatePayPalPayment($bookingId, $totalAmount)
{
    // Include PayPal SDK
    require 'vendor/autoload.php';

    // Set up PayPal API credentials
    $clientId = 'YOUR_PAYPAL_CLIENT_ID';
    $clientSecret = 'YOUR_PAYPAL_CLIENT_SECRET';

    // Create a new PayPal object
    $paypal = new \PayPal\Rest\ApiContext(
        new \PayPal\Auth\OAuthTokenCredential($clientId, $clientSecret)
    );

    // ... (the rest of the function remains the same)

    // Get the approval URL
    $approvalUrl = $payment->getApprovalLink();

    // Return the PayPal payment approval URL
    return $approvalUrl;
}

?>
