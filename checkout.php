<?php
session_start();

include_once 'connection.php';

if (isset($_SESSION['pickupNumber'])) {
    $pickupNumber = $_SESSION['pickupNumber'];
} else {
    $pickupNumber = 23;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/checkout.css">
    <title>Order overlook</title>
</head>

<body>
    <main>
        <img id="logo" src="assets/img/logo_big_complete_transparent.webp" alt="logo"> 
        <div id="orderOverlook">
            <h1>Order succesful!</h1>
            <div id="orderDetails">
                <p>#<?= $pickupNumber ?></p>
            </div>
            <p>Don't forget your receipt</p>
        </div>    
        <div id="automaticReturn">
            <p>Automatically returning to start..</p>
        </div>
        <script src="assets/js/checkout.js"></script>
    </main>

</body>

</html>