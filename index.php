<?php
session_start();
unset($_SESSION['cart'], $_SESSION['pickupNumber'], $_SESSION['languageOption']);

include_once 'connection.php';
require_once 'lang.php';
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars(getCurrentLanguage()); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/startscherm.css">
    <title>Start</title>
</head>

<body>

    <main class="start-page">
        <img id="bigLogo" src="assets/img/logo_big_complete_transparent.webp" alt="big logo">

         <div id="languageOptions">
            <div id="EnglishOption" data-language="English" class="languageOption">
                <img style="width: 90%; height: auto; margin-top: 15%;" src="assets/img/ENicon.png" alt="eat in icon"> <br>
                <p>English</p>
            </div>

            <div id="DutchOption" data-language="Dutch" class="languageOption">
                <img style="width: 90%; height: auto; margin-top: 15%;" src="assets/img/NLIcon.png" alt="take out icon"><br>
                <p>Nederlands</p>
            </div>
        </div>

        <div id="diningOptions">
            <div id="DiningIn" data-dining="DineIn" class="diningOption">
                <img style="width: 90%; height: auto; margin-top: 15%;" src="assets/img/eatInIcon.png" alt="eat in icon"> <br>
                <p class="diningLabel" data-key="dine_in">Dine In</p>
            </div>

            <div id="DiningOut" data-dining="TakeOut" class="diningOption">
                <img style="width: 90%; height: auto; margin-top: 15%;" src="assets/img/takeOutIcon.png" alt="take out icon"><br>
                <p class="diningLabel" data-key="take_out">Take Out</p>
            </div>
        </div>

    </main>
    <script src="assets/js/startscherm.js"></script>

</body>

</html>