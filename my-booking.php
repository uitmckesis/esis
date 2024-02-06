<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('includes/config.php');

// Function to update payment status in tblbooking and tblpayment tables
function updatePaymentStatus($bookingId, $newPaymentStatus) {
  global $dbh; // Assuming $dbh is your PDO database connection

  try {
      // Start a transaction
      $dbh->beginTransaction();

      // Update payment status in tblbooking table
      $updateSqlBooking = "UPDATE tblbooking SET paymentstatus = :newPaymentStatus WHERE bookingid = :bookingId";
      $updateQueryBooking = $dbh->prepare($updateSqlBooking);
      $updateQueryBooking->bindParam(':newPaymentStatus', $newPaymentStatus, PDO::PARAM_STR);
      $updateQueryBooking->bindParam(':bookingId', $bookingId, PDO::PARAM_INT);
      $updateQueryBooking->execute();

      // Update payment info in tblpayment table
      $paymentDate = date('Y-m-d');
      $paymentTime = date('H:i:s');
      $insertSqlPayment = "INSERT INTO tblpayment (bookingid, paymentstatus, paymentdate, paymenttime) VALUES (:bookingId, :newPaymentStatus, :paymentDate, :paymentTime)";
      $insertQueryPayment = $dbh->prepare($insertSqlPayment);
      $insertQueryPayment->bindParam(':bookingId', $bookingId, PDO::PARAM_INT);
      $insertQueryPayment->bindParam(':newPaymentStatus', $newPaymentStatus, PDO::PARAM_STR);
      $insertQueryPayment->bindParam(':paymentDate', $paymentDate, PDO::PARAM_STR);
      $insertQueryPayment->bindParam(':paymentTime', $paymentTime, PDO::PARAM_STR);
      $insertQueryPayment->execute();

      // Commit the transaction
      $dbh->commit();

      return true; // Return true if the update is successful
  } catch (PDOException $e) {
      // Rollback the transaction if an error occurs
      $dbh->rollback();
      // Handle the exception (e.g., log the error, return false)
      return false; // Return false if the update fails
  }
}

if(strlen($_SESSION['login'])==0)
  {
header('location:index.php');
}
else{
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="keywords" content="">
<meta name="description" content="">
<title>UiTMCK e-sis</title>
<!--Bootstrap -->
<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css">
<!--Custome Style -->
<link rel="stylesheet" href="assets/css/styles.css" type="text/css">
<!--OWL Carousel slider-->
<link rel="stylesheet" href="assets/css/owl.carousel.css" type="text/css">
<link rel="stylesheet" href="assets/css/owl.transitions.css" type="text/css">
<!--slick-slider -->
<link href="assets/css/slick.css" rel="stylesheet">
<!--bootstrap-slider -->
<link href="assets/css/bootstrap-slider.min.css" rel="stylesheet">
<!--FontAwesome Font Style -->
<link href="assets/css/font-awesome.min.css" rel="stylesheet">

<!--Paypal code -->
<script src="https://www.paypal.com/sdk/js?components=buttons,card-fields&client-id=AafzDTJ1PKIyWj6EC8Y6APfIqcLui9LNSr8cmpSjLQijqYUpISwGfJUq1xgspNrYs9P-ZC3X3JknVXQG"></script>

<!-- Fav and touch icons -->
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/images/favicon-icon/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/images/favicon-icon/apple-touch-icon-114-precomposed.html">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/images/favicon-icon/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="assets/images/favicon-icon/apple-touch-icon-57-precomposed.png">
<link rel="shortcut icon" href="assets/images/favicon-icon/24x24.png">
<!-- Google-Font-->
<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body>
<!--Header-->
<?php include('includes/header.php');?>
<!--Page Header-->
<!-- /Header -->

<!--Page Header-->
<section class="page-header profile_page">
  <div class="container">
    <div class="page-header_wrap">
      <div class="page-heading">
        <h1>My Booking</h1>
      </div>
      <ul class="coustom-breadcrumb">
        <li><a href="#">Home</a></li>
        <li>My Booking</li>
      </ul>
    </div>
  </div>
  <!-- Dark Overlay-->
  <div class="dark-overlay"></div>
</section>

<?php } ?>
<!-- /Page Header-->

<?php
$useremail=$_SESSION['login'];
$sql = "SELECT * from tblusers where EmailId=:useremail";
$query = $dbh -> prepare($sql);
$query -> bindParam(':useremail',$useremail, PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{ ?>
<section class="user_profile inner_pages">
  <div class="container">
    <div class="user_profile_info gray-bg padding_4x4_40">
      <div class="upload_user_logo"> <img src="assets/images/dealer-logo.jpg" alt="image">
      </div>

      <div class="dealer_info">
        <h5><?php echo htmlentities($result->FullName);?></h5>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3 col-sm-3">
       <?php include('includes/sidebar.php');?>

      <div class="col-md-6 col-sm-8">
        <div class="profile_wrap">
          <h5 class="uppercase underline">My Bookings </h5>
          <div class="my_vehicles_list">
            <ul class="vehicle_listing">
<?php
$useremail=$_SESSION['login'];
$useremail = $_SESSION['login'];
$sql = "SELECT tblvehicles.Vimage1 as Vimage1, tblvehicles.VehiclesTitle, tblvehicles.id as vid, tblbrands.BrandName, tblbooking.bookingid, tblbooking.FromDate, tblbooking.ToDate, tblbooking.message, tblbooking.Status, tblbooking.TotalPrice
        FROM tblbooking
        JOIN tblvehicles ON tblbooking.VehicleId = tblvehicles.id
        JOIN tblbrands ON tblbrands.id = tblvehicles.VehiclesBrand
        WHERE tblbooking.userEmail = :useremail";
$query = $dbh -> prepare($sql);
$query-> bindParam(':useremail', $useremail, PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);

if($query->rowCount() > 0) {
foreach($results as $result){
?>

<li>
                <div class="vehicle_img"> <a href="scooter-details.php?vhid=<?php echo htmlentities($result->vid);?>""><img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage1);?>" alt="image"></a> </div>
                <div class="vehicle_title">
                  <h6><a href="scooter-details.php?vhid=<?php echo htmlentities($result->vid);?>""> <?php echo htmlentities($result->BrandName);?> , <?php echo htmlentities($result->VehiclesTitle);?></a></h6>
                  <p><b>From Date:</b> <?php echo htmlentities($result->FromDate);?><br /> <b>To Date:</b> <?php echo htmlentities($result->ToDate);?></p>
                </div>
                <div style="float: left">
                   <p><b>Total Price:</b> RM <?php echo htmlentities($result->TotalPrice); ?> </p>
                </div>
                <div style="float: right"><p><b>Message:</b> <?php echo htmlentities($result->message);?> </p>
                <p><b> Pay Now Via: </b></p>

    <!-- Add these div elements to your body section -->
  <div id="paypal-button-container" class="paypal-button-container" data-amount="<?php echo htmlentities($result->TotalPrice); ?>"></div>
  <div id="checkout-form">
  <div id="card-name-field-container"></div>
  <div id="card-number-field-container"></div>
  <div id="card-expiry-field-container"></div>
  <div id="card-cvv-field-container"></div>
</div>


<?php } ?>

<?php }
?>
      </li>
  <?php
    }

    ?>
          </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div id="payment-status" style="display: none;">Paid</div>

<!-- Add this script to your body section -->

<script>
  var bookingAmount = document.querySelector('.paypal-button-container').getAttribute('data-amount');

  paypal.Buttons({
    createOrder: function (data, actions) {
      return actions.order.create({
        purchase_units: [{
          amount: {
            value: bookingAmount
          }
        }]
      });
    },
    onApprove: function (data, actions) {
      return actions.order.capture().then(function (details) {
        // Get the current date and time
        var transactionDateTime = new Date().toLocaleString();

        var receiptMessage = '**Payment Receipt**\n';
        receiptMessage += 'Transaction completed by ' + details.payer.name.given_name + '\n';
        receiptMessage += 'Transaction Date and Time: ' + transactionDateTime;

        // Display the receipt message
        alert(receiptMessage);

        // You can also display the receipt message in a separate div on your page
        // Example:
        // document.getElementById('receipt-message').innerText = receiptMessage;
      });
    }
  }).render('#paypal-button-container');
</script>



<!--/my-vehicles-->
<?php include('includes/footer.php');?>

<!-- Scripts -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/interface.js"></script>
<!--bootstrap-slider-JS-->
<script src="assets/js/bootstrap-slider.min.js"></script>
<!--Slider-JS-->
<script src="assets/js/slick.min.js"></script>
<script src="assets/js/owl.carousel.min.js"></script>
</body>
</html>
<?php } ?>