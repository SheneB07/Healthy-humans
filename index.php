<?php
session_start();

include_once 'connection.php';

$languageChoice = 'Dutch';

//prep for language switching. default is dutch, will rewrite later
if (isset($_SESSION['languageOption'])){
    if ($_SESSION['languageOption'] == 'English') {
        $languageChoice = 'English';
    } elseif ($_SESSION['languageOption'] == 'Dutch') {
        $languageChoice = 'Dutch';    
    }
}
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

        <!-- <div class="diningOptions">
            <div id="DiningIn" class="option">
                <img style="width: 90%; height: auto; margin-top: 15%;" src="assets/img/eatInIcon.png" alt="eat in icon"> <br>
                <p>Dine In</p>
            </div>
            <div id="DiningOut" class="option">
                <img style="width: 90%; height: auto; margin-top: 15%;" src="assets/img/takeOutIcon.png" alt="take out icon"><br>
                <p>Take Out</p>
            </div>
        </div> -->

        <div class="languageOptions">
            <div id="EnglishOption" class="option">
                <img style="width: 90%; height: auto; margin-top: 15%;" src="assets/img/ENicon.png" alt="eat in icon"> <br>
                <p>English</p>
            </div>
            <div id="DutchOption" class="option">
                <img style="width: 90%; height: auto; margin-top: 15%;" src="assets/img/NLIcon.png" alt="take out icon"><br>
                <p>Dutch</p>
            </div>
        </div>

    </main>

</body>

</html>