<?php
session_start();

include_once 'connection.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/css/style.css">
    <title>Start</title>
</head>

<body>

    <main class="start-page">
        <img class="bigLogo" src="assets/img/logo_big_complete_transparent.webp" alt="big logo">

        <div class="diningOptions">
            <div id="DiningIn" class="option">
                <img src="assets/img/eatInIcon.png" alt="eat in icon"> <br>
                <p>Dine In</p>
            </div>
            <div id="DiningOut" class="option">
                <img src="assets/img/takeOutIcon.png" alt="take out icon"><br>
                <p>Take Out</p>
            </div>
        </div>

        <!-- <div class="options">
            <h1>Where will you be eating today?</h1>

            <div class="dining-options">
                <form action="menu.php" method="post">
                    <input type="hidden" name="dineIn" value="1">
                    <button class="button" type="submit" name="dineChoiceButton">
                        <img class="option-img" src="assets/img/tray.png" alt="tray">
                        <div class="text-and-arrow">
                            <p>Dine in</p>
                            <img class="arrow" src="assets/img/arrow-right.svg" alt="arrow-right">
                        </div>
                    </button>
                </form>

                <form action="menu.php" method="post">
                    <input type="hidden" name="dineOut" value="2">
                    <button class="button" type="submit" name="dineChoiceButton">
                        <img class="option-img" src="assets/img/paper-bag.png" alt="paper-bag">
                        <div class="text-and-arrow">
                            <p>Take out</p>
                            <img class="arrow" src="assets/img/arrow-right.svg" alt="arrow-right">
                        </div>
                    </button>
                </form>
            </div>
        </div> -->
    </main>

</body>

</html>